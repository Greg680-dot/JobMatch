<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class AIServiceClient
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.ai_service.url', env('AI_SERVICE_URL', 'http://127.0.0.1:8000'));
    }

    /**
     * Vérifie la disponibilité du microservice Python.
     */
    public function healthCheck(): array
    {
        try {
            $response = Http::timeout(4)->get("{$this->baseUrl}/api/v1/health");
            if ($response->successful()) {
                return $response->json();
            }
            return ['status' => 'offline', 'error' => $response->body()];
        } catch (Exception $e) {
            Log::warning("AI Service unreachable: " . $e->getMessage());
            return ['status' => 'offline', 'error' => $e->getMessage()];
        }
    }

    /**
     * Envoie un fichier CV au service Python pour extraction structurée.
     */
    public function parseCV(string $fileContents, string $filename): array
    {
        try {
            $response = Http::timeout(25)
                ->attach('file', $fileContents, $filename)
                ->post("{$this->baseUrl}/api/v1/cv/parse");

            if ($response->successful()) {
                return $response->json();
            }
            throw new Exception("Erreur du service IA lors du parsing: " . $response->body());
        } catch (Exception $e) {
            Log::error("Erreur parseCV: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Calcule le score de matching vectoriel entre un CV et une liste d'opportunités.
     */
    public function calculateMatching(array $cvData, ?array $cvEmbedding, ?array $filters, array $opportunites): array
    {
        try {
            $payload = [
                'cv_data' => $cvData,
                'cv_embedding' => $cvEmbedding,
                'filters' => $filters,
                'opportunites' => $opportunites,
            ];

            $response = Http::timeout(20)->post("{$this->baseUrl}/api/v1/matching/score", $payload);

            if ($response->successful()) {
                return $response->json()['results'] ?? [];
            }
            throw new Exception("Erreur de calcul du matching: " . $response->body());
        } catch (Exception $e) {
            Log::error("Erreur calculateMatching: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Génère une lettre de motivation personnalisée et des conseils d'adaptation ATS.
     */
    public function generateLetter(array $candidateProfile, string $jobTitle, string $company, string $jobDescription, array $matchingSkills = [], string $tone = 'Professionnel et percutant'): array
    {
        try {
            $payload = [
                'candidate_profile' => $candidateProfile,
                'job_title' => $jobTitle,
                'company_name' => $company,
                'job_description' => $jobDescription,
                'matching_skills' => $matchingSkills,
                'tone' => $tone,
            ];

            $response = Http::timeout(35)->post("{$this->baseUrl}/api/v1/applications/generate-letter", $payload);

            if ($response->successful()) {
                return $response->json();
            }
            throw new Exception("Erreur de génération de lettre: " . $response->body());
        } catch (Exception $e) {
            Log::error("Erreur generateLetter: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Déclenche une collecte d'offres via flux RSS ou API.
     */
    public function collectOffers(string $sourceType = 'rss', ?string $targetUrl = null, ?string $category = null, int $maxResults = 15): array
    {
        try {
            $payload = [
                'source_type' => $sourceType,
                'target_url' => $targetUrl,
                'category' => $category,
                'max_results' => $maxResults,
            ];

            $response = Http::timeout(25)->post("{$this->baseUrl}/api/v1/collect/run", $payload);

            if ($response->successful()) {
                return $response->json()['offers'] ?? [];
            }
            throw new Exception("Erreur lors de la collecte d'offres: " . $response->body());
        } catch (Exception $e) {
            Log::error("Erreur collectOffers: " . $e->getMessage());
            throw $e;
        }
    }
}
