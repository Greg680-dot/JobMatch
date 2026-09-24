<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CV;
use App\Models\User;
use App\Models\Opportunite;
use App\Models\JobMatch;
use App\Models\ProfilRecherche;
use App\Services\AIServiceClient;
use Exception;

class CVController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user() ?: User::first();
        $cv = CV::where('user_id', $user->id)->where('is_default', true)->first();
        $profile = ProfilRecherche::where('user_id', $user->id)->first();

        return view('profile', compact('user', 'cv', 'profile'));
    }

    public function upload(Request $request, AIServiceClient $aiClient)
    {
        $request->validate([
            'cv_file' => 'required|file|mimes:pdf,docx,txt|max:10240',
        ]);

        $user = $request->user() ?: User::first();
        $file = $request->file('cv_file');
        $filename = $file->getClientOriginalName();
        $content = file_get_contents($file->getRealPath());

        try {
            $defaultName = $user ? $user->name : 'Candidat';

            // 1. Parsing haute fidélité (autonome ou microservice)
            $parseResult = $aiClient->parseCV($content, $filename, $defaultName);
            
            // Si le nom extrait du CV est un nom réel, actualiser le profil utilisateur
            $extractedName = $parseResult['data']['nom'] ?? null;
            if ($user && !empty($extractedName) && !in_array(strtolower($extractedName), ['candidat', 'candidat démo', 'candidat demo'])) {
                $user->name = $extractedName;
                if (!empty($parseResult['data']['email']) && filter_var($parseResult['data']['email'], FILTER_VALIDATE_EMAIL) && in_array($user->email, ['candidat.demo@jobmatch.ai', 'alexandre.martin@example.com'])) {
                    $user->email = $parseResult['data']['email'];
                }
                $user->save();
            }

            // 2. Mise à jour ou création du CV
            CV::where('user_id', $user->id)->update(['is_default' => false]);

            $cv = CV::create([
                'user_id' => $user->id,
                'original_filename' => $filename,
                'parsed_data' => $parseResult['data'],
                'embedding' => $parseResult['embedding'],
                'is_default' => true,
            ]);

            // Synchroniser le type d'opportunité recherché si non encore personnalisé
            if (!empty($parseResult['data']['titre_professionnel'])) {
                $profile = ProfilRecherche::firstOrCreate(['user_id' => $user->id]);
                if (empty($profile->type_opportunite)) {
                    $profile->type_opportunite = $parseResult['data']['titre_professionnel'];
                    $profile->save();
                }
            }

            // 3. Recalcul automatique du matching avec les opportunités existantes
            $this->recalculateMatches($user, $cv, $aiClient);

            return back()->with('success', "CV '{$filename}' analysé avec succès. Votre profil, vos compétences et les scores d'opportunités ont été actualisés.");
        } catch (Exception $e) {
            return back()->with('error', "Erreur lors de l'analyse du CV : " . $e->getMessage());
        }
    }

    public function updatePreferences(Request $request, AIServiceClient $aiClient)
    {
        $user = $request->user() ?: User::first();

        $validated = $request->validate([
            'type_opportunite' => 'nullable|string|max:150',
            'pays' => 'nullable|string|max:100',
            'types_contrat' => 'nullable|array',
            'types_contrat.*' => 'string|in:CDI,CDD,FREELANCE,STAGE,ALTERNANCE,TEMPS_PARTIEL',
            'salaire_min' => 'nullable|numeric|min:0',
            'teletravail_only' => 'nullable|boolean',
        ]);

        $typesContrat = $request->input('types_contrat', []);
        $teletravailOnly = $request->boolean('teletravail_only');

        $profile = ProfilRecherche::updateOrCreate(
            ['user_id' => $user->id],
            [
                'type_opportunite' => $validated['type_opportunite'] ?? null,
                'pays' => $validated['pays'] ?? null,
                'types_contrat' => !empty($typesContrat) ? $typesContrat : null,
                'localisations' => !empty($validated['pays']) ? [$validated['pays']] : null,
                'salaire_min' => $validated['salaire_min'] ?? null,
                'teletravail_only' => $teletravailOnly,
            ]
        );

        // Recalcul automatique des opportunités si un CV existe
        $cv = CV::where('user_id', $user->id)->where('is_default', true)->first();
        if ($cv) {
            $this->recalculateMatches($user, $cv, $aiClient);
        }

        return back()->with('success', 'Vos critères de recherche (type d\'opportunité, pays et contrat) ont été enregistrés avec succès. Les scores de pertinence ont été mis à jour.');
    }

    public function recalculateMatches(User $user, CV $cv, AIServiceClient $aiClient): void
    {
        $opportunites = Opportunite::all();
        if ($opportunites->isEmpty()) {
            return;
        }

        $profile = ProfilRecherche::where('user_id', $user->id)->first();
        $filters = $profile ? [
            'type_opportunite' => $profile->type_opportunite,
            'pays' => $profile->pays,
            'types_contrat' => $profile->types_contrat,
            'keywords_excluded' => $profile->keywords_excluded,
            'salaire_min' => $profile->salaire_min,
            'teletravail_only' => $profile->teletravail_only,
        ] : null;

        $oppsPayload = $opportunites->map(function ($opp) {
            return [
                'id' => (string)$opp->id,
                'titre' => $opp->titre,
                'entreprise' => $opp->entreprise,
                'localisation' => $opp->localisation,
                'type_contrat' => $opp->type_contrat,
                'description' => $opp->description,
                'salaire_indicatif' => $opp->salaire_indicatif,
                'teletravail' => $opp->teletravail,
                'embedding' => $opp->embedding,
            ];
        })->toArray();

        try {
            $results = $aiClient->calculateMatching($cv->parsed_data, $cv->embedding, $filters, $oppsPayload);

            foreach ($results as $r) {
                JobMatch::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'opportunite_id' => (int)$r['opportunite_id'],
                    ],
                    [
                        'score_pertinence' => $r['score_pertinence'],
                        'passed_hard_filters' => $r['passed_hard_filters'],
                        'rejection_reason' => $r['rejection_reason'],
                        'matching_skills' => $r['matching_skills'],
                        'missing_skills' => $r['missing_skills'],
                        'summary_explanation' => $r['summary_explanation'],
                    ]
                );
            }
        } catch (Exception $e) {
            \Log::warning("Service IA distant indisponible pour le recalcul, application du moteur d'évaluation local: " . $e->getMessage());

            // Moteur de matching local résilient
            $cvSkills = $cv->parsed_data['competences_techniques'] ?? [];
            $cvTitle = strtolower($cv->parsed_data['titre_professionnel'] ?? '');

            foreach ($opportunites as $opp) {
                $passedHard = true;
                $rejectionReason = null;

                // Filtre Contrat
                if ($profile && !empty($profile->types_contrat)) {
                    $allowedContracts = array_map('strtoupper', $profile->types_contrat);
                    if (!in_array(strtoupper($opp->type_contrat), $allowedContracts)) {
                        $passedHard = false;
                        $rejectionReason = "Contrat '{$opp->type_contrat}' hors sélection";
                    }
                }

                // Filtre Pays / Localisation
                if ($passedHard && $profile && !empty($profile->pays)) {
                    $wantedCountry = strtolower(trim($profile->pays));
                    if (!in_array($wantedCountry, ['tous', 'international', 'monde', 'partout'])) {
                        $oppLoc = strtolower($opp->localisation);
                        if (stripos($oppLoc, $wantedCountry) === false && !$opp->teletravail) {
                            $passedHard = false;
                            $rejectionReason = "Localisation '{$opp->localisation}' non située dans le pays souhaité ({$profile->pays})";
                        }
                    }
                }

                // Calcul compétences correspondantes
                $matchedSkills = [];
                $descLower = strtolower($opp->description . ' ' . $opp->titre);
                foreach ($cvSkills as $skill) {
                    if (stripos($descLower, strtolower($skill)) !== false) {
                        $matchedSkills[] = $skill;
                    }
                }

                // Calcul de score de base
                $baseScore = !empty($cvSkills) ? (count($matchedSkills) / max(1, count($cvSkills))) * 70 + 20 : 50;

                // Bonus type d'opportunité souhaité
                if ($profile && !empty($profile->type_opportunite)) {
                    if (stripos(strtolower($opp->titre), strtolower($profile->type_opportunite)) !== false) {
                        $baseScore += 15;
                    }
                }

                $score = $passedHard ? round(min(98.0, max(20.0, $baseScore)), 1) : 0.0;

                JobMatch::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'opportunite_id' => $opp->id,
                    ],
                    [
                        'score_pertinence' => $score,
                        'passed_hard_filters' => $passedHard,
                        'rejection_reason' => $rejectionReason,
                        'matching_skills' => $matchedSkills,
                        'missing_skills' => [],
                        'summary_explanation' => $passedHard 
                            ? "Match évalué à {$score}% avec vos compétences et critères de recherche." 
                            : "Offre écartée : {$rejectionReason}.",
                    ]
                );
            }
        }
    }
}
