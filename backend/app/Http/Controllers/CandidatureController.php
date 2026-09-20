<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidature;
use App\Models\Opportunite;
use App\Models\JobMatch;
use App\Models\User;
use App\Models\CV;
use App\Services\AIServiceClient;
use Exception;

class CandidatureController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user() ?: User::first();

        $activeTab = $request->query('tab', 'all');
        $search = $request->query('q', '');

        // Base query for user candidatures
        $query = Candidature::with(['opportunite', 'match'])
            ->where('user_id', $user->id);

        // Filter by tab
        if ($activeTab === 'deposees') {
            $query->whereIn('statut', ['envoyee', 'relancee', 'entretien', 'refusee', 'acceptee']);
        } elseif ($activeTab === 'en_attente') {
            $query->whereIn('statut', ['envoyee', 'relancee']);
        } elseif ($activeTab === 'entretien') {
            $query->where('statut', 'entretien');
        } elseif ($activeTab === 'brouillon') {
            $query->whereIn('statut', ['brouillon', 'validee']);
        }

        // Search filter
        if (!empty($search)) {
            $query->whereHas('opportunite', function ($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhere('entreprise', 'like', "%{$search}%")
                  ->orWhere('localisation', 'like', "%{$search}%");
            });
        }

        $candidatures = $query->orderByDesc('updated_at')->get();

        // Calculate all stats for user
        $allUserCandidatures = Candidature::where('user_id', $user->id)->get();
        $counts = [
            'total' => $allUserCandidatures->count(),
            'deposees' => $allUserCandidatures->whereIn('statut', ['envoyee', 'relancee', 'entretien', 'refusee', 'acceptee'])->count(),
            'en_attente' => $allUserCandidatures->whereIn('statut', ['envoyee', 'relancee'])->count(),
            'entretiens' => $allUserCandidatures->where('statut', 'entretien')->count(),
            'brouillons' => $allUserCandidatures->whereIn('statut', ['brouillon', 'validee'])->count(),
        ];

        return view('candidatures', compact('candidatures', 'counts', 'activeTab', 'search'));
    }

    public function generate(Request $request, Opportunite $opportunite, AIServiceClient $aiClient)
    {
        $user = $request->user() ?: User::first();
        $cv = CV::where('user_id', $user->id)->where('is_default', true)->first();

        if (!$cv) {
            return redirect()->route('profile.show')->with('warning', 'Veuillez d\'abord téléverser votre CV avant de générer une candidature.');
        }

        // Vérifie si une candidature existe déjà pour cette offre
        $candidature = Candidature::where('user_id', $user->id)
            ->where('opportunite_id', $opportunite->id)
            ->first();

        $match = JobMatch::where('user_id', $user->id)
            ->where('opportunite_id', $opportunite->id)
            ->first();

        if (!$candidature) {
            try {
                $matchingSkills = $match ? ($match->matching_skills ?? []) : [];
                $genResult = $aiClient->generateLetter(
                    $cv->parsed_data,
                    $opportunite->titre,
                    $opportunite->entreprise,
                    $opportunite->description,
                    $matchingSkills
                );

                $candidature = Candidature::create([
                    'user_id' => $user->id,
                    'opportunite_id' => $opportunite->id,
                    'match_id' => $match ? $match->id : null,
                    'objet_email' => $genResult['object_email'] ?? "Candidature : {$opportunite->titre}",
                    'lettre_motivation' => $genResult['cover_letter'],
                    'cv_adaptation_tips' => $genResult['cv_adaptation_tips'] ?? [],
                    'suggested_skills' => $genResult['suggested_skills_to_highlight'] ?? [],
                    'statut' => 'brouillon',
                ]);
            } catch (Exception $e) {
                return back()->with('error', "Impossible de générer la lettre : " . $e->getMessage());
            }
        }

        return view('candidature_editor', compact('candidature', 'opportunite', 'match', 'cv'));
    }

    public function update(Request $request, Candidature $candidature)
    {
        $validated = $request->validate([
            'lettre_motivation' => 'required|string',
            'objet_email' => 'nullable|string|max:255',
            'statut' => 'required|in:brouillon,validee,envoyee,relancee,entretien,refusee,acceptee',
            'notes_candidat' => 'nullable|string',
        ]);

        if ($validated['statut'] === 'envoyee' && !$candidature->date_envoi) {
            $validated['date_envoi'] = now();
        }

        $candidature->update($validated);

        return back()->with('success', 'Candidature mise à jour avec succès.');
    }

    public function updateStatus(Request $request, Candidature $candidature)
    {
        $validated = $request->validate([
            'statut' => 'required|in:brouillon,validee,envoyee,relancee,entretien,refusee,acceptee',
        ]);

        if ($validated['statut'] === 'envoyee' && !$candidature->date_envoi) {
            $validated['date_envoi'] = now();
        }

        $candidature->update($validated);

        return back()->with('success', 'Statut de suivi actualisé.');
    }
}
