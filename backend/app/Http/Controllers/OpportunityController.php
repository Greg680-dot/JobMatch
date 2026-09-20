<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Opportunite;
use App\Models\Source;
use App\Models\User;
use App\Models\CV;
use App\Services\AIServiceClient;
use Exception;

class OpportunityController extends Controller
{
    public function collect(Request $request, AIServiceClient $aiClient)
    {
        $sourceType = $request->input('source_type', 'rss');
        $targetUrl = $request->input('target_url');

        try {
            $offers = $aiClient->collectOffers($sourceType, $targetUrl, 'développeur', 15);
            $newCount = 0;

            foreach ($offers as $o) {
                $opp = Opportunite::updateOrCreate(
                    ['deduplication_hash' => $o['deduplication_hash']],
                    [
                        'titre' => $o['titre'],
                        'entreprise' => $o['entreprise'],
                        'localisation' => $o['localisation'],
                        'type_contrat' => $o['type_contrat'],
                        'description' => $o['description'],
                        'url_source' => $o['url_source'],
                        'date_publication' => $o['date_publication'],
                        'embedding' => $o['embedding'] ?? null,
                    ]
                );
                if ($opp->wasRecentlyCreated) {
                    $newCount++;
                }
            }

            // Recalcul des matches avec le CV actif
            $user = User::first();
            $cv = CV::where('user_id', $user->id)->where('is_default', true)->first();
            if ($cv) {
                app(CVController::class)->recalculateMatches($user, $cv, $aiClient);
            }

            return back()->with('success', "Collecte terminée avec succès : {$newCount} nouvelle(s) opportunité(s) ajoutée(s).");
        } catch (Exception $e) {
            return back()->with('error', "Erreur lors de la collecte : " . $e->getMessage());
        }
    }
}
