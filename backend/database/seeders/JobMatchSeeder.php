<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\CV;
use App\Models\ProfilRecherche;
use App\Models\Opportunite;
use App\Models\JobMatch;
use App\Models\Candidature;
use App\Services\BeninAfricaJobService;

class JobMatchSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Création de l'utilisateur candidat principal (configurable via variables d'environnement)
        $customEmail = env('DEFAULT_USER_EMAIL', env('ADMIN_EMAIL'));
        $customPassword = env('DEFAULT_USER_PASSWORD', env('ADMIN_PASSWORD'));
        $customName = env('DEFAULT_USER_NAME', env('ADMIN_NAME', 'Candidat'));

        if (!empty($customEmail) && !empty($customPassword)) {
            $user = User::updateOrCreate(
                ['email' => $customEmail],
                [
                    'name' => $customName,
                    'password' => bcrypt($customPassword),
                ]
            );
        } else {
            $user = User::updateOrCreate(
                ['email' => 'candidat.demo@jobmatch.ai'],
                [
                    'name' => 'Candidat Démo',
                    'password' => bcrypt('password123'),
                ]
            );
        }

        // Compte démo toujours maintenu en secours pour test immédiat
        User::updateOrCreate(
            ['email' => 'candidat.demo@jobmatch.ai'],
            [
                'name' => 'Candidat Démo',
                'password' => bcrypt('password123'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'alexandre.martin@example.com'],
            [
                'name' => 'Candidat Démo',
                'password' => bcrypt('password123'),
            ]
        );

        // 2. Création du CV démo structuré
        $cvData = [
            'nom' => 'Candidat Démo',
            'email' => 'candidat.demo@jobmatch.ai',
            'telephone' => '+229 01 97 00 00 00',
            'titre_professionnel' => 'Ingénieur d\'Études & Développement Logiciel',
            'annees_experience' => 4,
            'competences_techniques' => [
                'Architecture Logicielle', 'Conception Web & Cloud', 'Méthodologies Agiles', 
                'CI/CD & Qualité', 'Bases de Données', 'APIs & Services Web', 
                'Cybersécurité', 'Optimisation Performance'
            ],
            'competences_soft' => ['Rigueur', 'Autonomie', 'Esprit d\'équipe', 'Méthode Agile'],
            'experiences' => [
                [
                    'poste' => 'Ingénieur d\'Études & Conception Web',
                    'entreprise' => 'Digital Solutions & Services',
                    'periode' => '2022 - 2026',
                    'description' => 'Conception et développement de plateformes métiers performantes. Modélisation de bases de données, création d\'APIs robustes et mise en place de tests automatisés.'
                ],
                [
                    'poste' => 'Développeur d\'Applications & Services',
                    'entreprise' => 'NovaTech Studio',
                    'periode' => '2020 - 2022',
                    'description' => 'Développement de fonctionnalités applicatives, intégration continue et maintenance évolutive de solutions numériques.'
                ]
            ],
            'formations' => [
                [
                    'diplome' => 'Master Informatique & Génie Logiciel',
                    'etablissement' => 'Institut Polytechnique / Université',
                    'annee' => '2020'
                ]
            ],
            'langues' => ['Français (Natif)', 'Anglais (Professionnel)']
        ];

        $cv = CV::updateOrCreate(
            ['user_id' => $user->id, 'is_default' => true],
            [
                'original_filename' => 'CV_Candidat_Demo.pdf',
                'raw_text' => 'Candidat Démo - Ingénieur d\'Études & Développement Logiciel - Cotonou, Bénin...',
                'parsed_data' => $cvData,
                'embedding' => array_fill(0, 64, 0.12),
                'is_default' => true,
            ]
        );

        // 3. Objectifs de recherche ciblés Bénin & Afrique
        ProfilRecherche::updateOrCreate(
            ['user_id' => $user->id],
            [
                'type_opportunite' => 'Ingénieur d\'Études & Développement Logiciel',
                'pays' => 'Bénin',
                'types_contrat' => ['CDI', 'FREELANCE'],
                'localisations' => ['Bénin', 'Afrique de l\'Ouest', 'Télétravail'],
                'salaire_min' => 6000000,
                'keywords_must' => ['Architecture', 'APIs'],
                'keywords_excluded' => ['COBOL', 'SAP'],
                'teletravail_only' => false,
            ]
        );

        // 4. Chargement des offres réelles Bénin, Afrique de l'Ouest et Remote
        $jobService = app(BeninAfricaJobService::class);
        $realOffers = $jobService->collectAll('all');

        $cvSkills = $cvData['competences_techniques'];

        foreach ($realOffers as $oppData) {
            $opp = Opportunite::updateOrCreate(
                ['deduplication_hash' => $oppData['deduplication_hash']],
                [
                    'titre' => $oppData['titre'],
                    'entreprise' => $oppData['entreprise'],
                    'localisation' => $oppData['localisation'],
                    'type_contrat' => $oppData['type_contrat'],
                    'description' => $oppData['description'],
                    'salaire_indicatif' => $oppData['salaire_indicatif'] ?? null,
                    'teletravail' => $oppData['teletravail'] ?? false,
                    'url_source' => $oppData['url_source'],
                    'date_publication' => $oppData['date_publication'],
                ]
            );

            // Calcul du matching sémantique avec le CV
            $matchingSkills = array_values(array_intersect($cvSkills, $oppData['skills'] ?? []));
            if (empty($matchingSkills)) {
                $matchingSkills = ['Architecture Logicielle', 'APIs & Services Web'];
            }

            $allSkills = ['Architecture Logicielle', 'Conception Web & Cloud', 'APIs & Services Web', 'Bases de Données', 'Cybersécurité', 'CI/CD & Qualité'];
            $missingSkills = array_values(array_diff($oppData['skills'] ?? [], $cvSkills));
            if (empty($missingSkills)) {
                $missingSkills = ['Certifications Spécifiques'];
            }

            // Score proportionnel aux compétences et localisation
            $isBeninOrAfrica = str_contains($opp->localisation, 'Bénin') || str_contains($opp->localisation, 'Cotonou') || str_contains($opp->localisation, 'Afrique');
            $score = 70.0 + (count($matchingSkills) * 5.0) + ($isBeninOrAfrica ? 5.0 : 0.0);
            $score = min(96.5, max(62.0, $score));

            // Création du match associé
            $match = JobMatch::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'opportunite_id' => $opp->id,
                ],
                [
                    'score_pertinence' => $score,
                    'passed_hard_filters' => true,
                    'matching_skills' => $matchingSkills,
                    'missing_skills' => $missingSkills,
                    'summary_explanation' => "Match à " . number_format($score, 1) . "% : forte adéquation des compétences en " . implode(', ', array_slice($matchingSkills, 0, 3)) . " pour " . $opp->entreprise . ".",
                    'statut' => $score > 85 ? 'favori' : 'nouveau',
                ]
            );

            // Création d'une candidature pour illustrer le suivi (MTN Bénin, Moov Bénin, Sèmè City)
            if ($opp->entreprise === 'MTN Bénin') {
                Candidature::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'opportunite_id' => $opp->id,
                    ],
                    [
                        'match_id' => $match->id,
                        'objet_email' => "Candidature : {$opp->titre} - Candidat Démo",
                        'lettre_motivation' => "Candidat Démo\ncandidat.demo@jobmatch.ai | +229 01 97 00 00 00\n\nÀ l'attention de la Direction des Ressources Humaines\nMTN Bénin\nCotonou, Bénin\n\nObjet : Candidature au poste de {$opp->titre}\n\nMadame, Monsieur,\n\nC'est avec un très vif intérêt que je postule au poste de {$opp->titre} au sein de MTN Bénin. Passionné par l'écosystème numérique béninois et les solutions de Mobile Money et services télécoms, je souhaite apporter mon savoir-faire technique à vos équipes.\n\nFort de 4 ans d'expérience en ingénierie logicielle, j'ai développé une maîtrise solide en conception d'architectures résilientes, APIs de paiement et bases de données à haute disponibilité.\n\nRestant à votre disposition pour un entretien,\n\nCandidat Démo",
                        'cv_adaptation_tips' => [
                            "Mettez en valeur votre expérience sur les APIs de paiement et la scalabilité télécom.",
                            "Précisez vos compétences en architecture microservices et conteneurs Docker.",
                            "Mentionnez votre rigueur sur la haute disponibilité."
                        ],
                        'suggested_skills' => $matchingSkills,
                        'statut' => 'entretien',
                        'date_envoi' => now()->subDays(4),
                        'notes_candidat' => 'Entretien technique prévu avec l\'équipe DSI de MTN Bénin.',
                    ]
                );
            } elseif ($opp->entreprise === 'Sèmè City (Cité Internationale de l\'Innovation et du Savoir)') {
                Candidature::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'opportunite_id' => $opp->id,
                    ],
                    [
                        'match_id' => $match->id,
                        'objet_email' => "Candidature : {$opp->titre} - Candidat Démo",
                        'lettre_motivation' => "Candidat Démo\ncandidat.demo@jobmatch.ai | +229 01 97 00 00 00\n\nÀ l'attention de l'équipe de recrutement\nSèmè City Bénin\n\nMadame, Monsieur,\n\nCandidature enthousiaste pour le poste de {$opp->titre}. Très motivé à l'idée de contribuer à la cité de l'innovation et du savoir au Bénin avec des technologies full-stack modernes.\n\nCordialement,\nCandidat Démo",
                        'cv_adaptation_tips' => [
                            "Mettre en avant les projets d'innovation et le leadership technique.",
                            "Préciser l'expérience full stack Laravel / Vue.js."
                        ],
                        'suggested_skills' => $matchingSkills,
                        'statut' => 'envoyee',
                        'date_envoi' => now()->subDays(2),
                        'notes_candidat' => 'Dossier transmis via la plateforme Sèmè City.',
                    ]
                );
            } elseif ($opp->entreprise === 'Wave Digital Finance') {
                Candidature::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'opportunite_id' => $opp->id,
                    ],
                    [
                        'match_id' => $match->id,
                        'objet_email' => "Candidature : {$opp->titre} - Candidat Démo",
                        'lettre_motivation' => "Candidat Démo\ncandidat.demo@jobmatch.ai\n\nMadame, Monsieur,\n\nCandidature au poste de {$opp->titre} chez Wave Digital Finance pour participer à l'expansion des services financiers en Afrique de l'Ouest.\n\nCordialement,\nCandidat Démo",
                        'cv_adaptation_tips' => [
                            "Démontrer l'expérience en systèmes transactionnels et scalabilité.",
                        ],
                        'suggested_skills' => $matchingSkills,
                        'statut' => 'brouillon',
                        'notes_candidat' => 'Brouillon prêt à valider.',
                    ]
                );
            }
        }
    }
}
