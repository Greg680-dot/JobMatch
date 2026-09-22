<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobMatch;
use App\Models\Opportunite;
use App\Models\Candidature;
use App\Models\CV;
use App\Models\User;
use App\Models\ProfilRecherche;
use App\Services\AIServiceClient;

class DashboardController extends Controller
{
    public function index(Request $request, AIServiceClient $aiClient)
    {
        // Récupère l'utilisateur connecté
        $user = $request->user() ?: User::first();

        // Vérification de santé du service IA
        $aiHealth = $aiClient->healthCheck();

        // Récupération du CV par défaut et du profil de recherche
        $cv = CV::where('user_id', $user->id)->where('is_default', true)->first();
        $profile = ProfilRecherche::where('user_id', $user->id)->first();

        // Requête sur les matches
        $query = JobMatch::with('opportunite')
            ->where('user_id', $user->id);

        // Filtre de statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        } else {
            $query->where('statut', '!=', 'ignore');
        }

        // Filtre score minimum
        if ($request->filled('score_min')) {
            $query->where('score_pertinence', '>=', (float)$request->score_min);
        }

        // Filtre Région / Zone géographique (Bénin, Afrique de l'Ouest, Télétravail)
        $region = $request->query('region', 'all');
        if ($region === 'benin') {
            $query->whereHas('opportunite', function ($q) {
                $q->where('localisation', 'like', '%Bénin%')
                  ->orWhere('localisation', 'like', '%Cotonou%')
                  ->orWhere('localisation', 'like', '%Calavi%')
                  ->orWhere('localisation', 'like', '%Porto-Novo%');
            });
        } elseif ($region === 'afrique') {
            $query->whereHas('opportunite', function ($q) {
                $q->where('localisation', 'like', '%Bénin%')
                  ->orWhere('localisation', 'like', '%Sénégal%')
                  ->orWhere('localisation', 'like', '%Côte d\'Ivoire%')
                  ->orWhere('localisation', 'like', '%Togo%')
                  ->orWhere('localisation', 'like', '%Dakar%')
                  ->orWhere('localisation', 'like', '%Abidjan%')
                  ->orWhere('localisation', 'like', '%Lomé%')
                  ->orWhere('localisation', 'like', '%Afrique%');
            });
        } elseif ($region === 'remote') {
            $query->whereHas('opportunite', function ($q) {
                $q->where('teletravail', true)
                  ->orWhere('localisation', 'like', '%Télétravail%')
                  ->orWhere('localisation', 'like', '%Remote%');
            });
        }

        // Filtre Recherche textuelle
        if ($request->filled('q')) {
            $search = $request->q;
            $query->whereHas('opportunite', function ($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhere('entreprise', 'like', "%{$search}%")
                  ->orWhere('localisation', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $matches = $query->orderBy('score_pertinence', 'desc')->get();

        // Statistiques
        $stats = [
            'total_opportunites' => Opportunite::count(),
            'high_matches' => JobMatch::where('user_id', $user->id)->where('score_pertinence', '>=', 70)->count(),
            'candidatures_en_cours' => Candidature::where('user_id', $user->id)->whereIn('statut', ['brouillon', 'validee', 'envoyee'])->count(),
            'entretiens' => Candidature::where('user_id', $user->id)->where('statut', 'entretien')->count(),
            'benin_count' => Opportunite::where('localisation', 'like', '%Bénin%')->orWhere('localisation', 'like', '%Cotonou%')->count(),
            'afrique_count' => Opportunite::where('localisation', 'like', '%Afrique%')
                ->orWhere('localisation', 'like', '%Bénin%')
                ->orWhere('localisation', 'like', '%Sénégal%')
                ->orWhere('localisation', 'like', '%Côte d\'Ivoire%')
                ->orWhere('localisation', 'like', '%Togo%')
                ->count(),
        ];

        // Candidatures Kanban
        $candidatures = Candidature::with('opportunite')
            ->where('user_id', $user->id)
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('dashboard', compact('user', 'cv', 'matches', 'stats', 'candidatures', 'aiHealth', 'profile', 'region'));
    }

    public function updateMatchStatus(Request $request, JobMatch $match)
    {
        $validated = $request->validate([
            'statut' => 'required|in:nouveau,favori,ignore,en_cours',
        ]);

        $match->update($validated);
        return back()->with('success', 'Statut de l\'opportunité mis à jour.');
    }
}
