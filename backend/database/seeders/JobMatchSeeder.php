<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\CV;
use App\Models\ProfilRecherche;
use App\Models\Opportunite;
use App\Models\JobMatch;
use App\Models\Candidature;

class JobMatchSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Création de l'utilisateur candidat démo
        $user = User::updateOrCreate(
            ['email' => 'candidat.demo@jobmatch.ai'],
            [
                'name' => 'Candidat Démo',
                'password' => bcrypt('password123'),
            ]
        );

        // Alias pour rétrocompatibilité éventuelle
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
            'telephone' => '06 00 00 00 00',
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
                    'entreprise' => 'Digital Solutions SAS',
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
                    'etablissement' => 'Université des Sciences & Technologies',
                    'annee' => '2020'
                ]
            ],
            'langues' => ['Français (Natif)', 'Anglais (Professionnel C1)']
        ];

        $cv = CV::updateOrCreate(
            ['user_id' => $user->id, 'is_default' => true],
            [
                'original_filename' => 'CV_Candidat_Demo.pdf',
                'raw_text' => 'Candidat Démo - Ingénieur d\'Études & Développement Logiciel...',
                'parsed_data' => $cvData,
                'embedding' => array_fill(0, 64, 0.12),
                'is_default' => true,
            ]
        );

        // 3. Objectifs de recherche
        ProfilRecherche::updateOrCreate(
            ['user_id' => $user->id],
            [
                'type_opportunite' => 'Ingénieur d\'Études & Développement',
                'pays' => 'France',
                'types_contrat' => ['CDI', 'FREELANCE'],
                'localisations' => ['France', 'Télétravail'],
                'salaire_min' => 42000,
                'keywords_must' => ['Architecture', 'APIs'],
                'keywords_excluded' => ['COBOL', 'SAP'],
                'teletravail_only' => false,
            ]
        );

        // 4. Création des opportunités de test
        $opportunitiesData = [
            [
                'titre' => 'Ingénieur d\'Études & Développement Logiciel (H/F)',
                'entreprise' => 'InnoTech Solutions',
                'localisation' => 'Paris / Télétravail partiel',
                'type_contrat' => 'CDI',
                'description' => 'Nous recherchons un Ingénieur d\'Études et Développement confirmé. Vous participerez activement à la conception et au déploiement de notre plateforme SaaS métier. Compétences attendues : Architecture Logicielle, APIs Web, Bases de Données relationnelles, Méthodologies Agiles et Qualité logicielle.',
                'salaire_indicatif' => 52000,
                'teletravail' => true,
                'url_source' => 'https://example.com/jobs/innotech-fullstack',
                'deduplication_hash' => md5('innotech_fullstack_2026'),
                'date_publication' => date('Y-m-d'),
                'score' => 92.5,
                'matching_skills' => ['Architecture Logicielle', 'APIs & Services Web', 'Bases de Données', 'Méthodologies Agiles'],
                'missing_skills' => ['Sécurité Avancée', 'Cloud Hybride'],
                'summary' => 'Match exceptionnel à 92.5% : adéquation directe sur l\'architecture, les APIs et la conception de données.',
            ],
            [
                'titre' => 'Chef de Projet Technique & Solutions Numériques',
                'entreprise' => 'DataFlow Solutions',
                'localisation' => 'Lyon / Télétravail complet',
                'type_contrat' => 'CDI',
                'description' => 'Pilotage de projets numériques, conception d\'architectures logicielles performantes, traitement de flux de données et encadrement technique.',
                'salaire_indicatif' => 55000,
                'teletravail' => true,
                'url_source' => 'https://example.com/jobs/dataflow-solutions',
                'deduplication_hash' => md5('dataflow_solutions_2026'),
                'date_publication' => date('Y-m-d'),
                'score' => 84.0,
                'matching_skills' => ['Architecture Logicielle', 'Bases de Données', 'Méthodologies Agiles'],
                'missing_skills' => ['Gouvernance Data', 'Audit Qualité'],
                'summary' => 'Match élevé à 84.0% : vos expériences en ingénierie et gestion de projet correspondent au profil recherché.',
            ],
            [
                'titre' => 'Consultant Ingénierie & Systèmes d\'Information',
                'entreprise' => 'Digital Partners',
                'localisation' => 'Télétravail complet',
                'type_contrat' => 'FREELANCE',
                'description' => 'Mission de conseil et développement pour la refonte d\'un portail client sécurisé, évolutif et orienté services.',
                'salaire_indicatif' => 60000,
                'teletravail' => true,
                'url_source' => 'https://example.com/jobs/digital-freelance',
                'deduplication_hash' => md5('digital_freelance_2026'),
                'date_publication' => date('Y-m-d'),
                'score' => 78.5,
                'matching_skills' => ['APIs & Services Web', 'Conception Web & Cloud'],
                'missing_skills' => ['Gouvernance Cloud', 'Architecture Micro-Frontends'],
                'summary' => 'Match à 78.5% : mission adaptée à vos compétences en conception de services et qualité logicielle.',
            ],
            [
                'titre' => 'Lead Concepteur d\'Applications Numériques',
                'entreprise' => 'MediaGroup France',
                'localisation' => 'Bordeaux / Hybride',
                'type_contrat' => 'CDI',
                'description' => 'Conception et maintenance d\'applications web à fort trafic, optimisation des flux de données et intégration continue.',
                'salaire_indicatif' => 46000,
                'teletravail' => false,
                'url_source' => 'https://example.com/jobs/mediagroup-concepteur',
                'deduplication_hash' => md5('mediagroup_concepteur_2026'),
                'date_publication' => date('Y-m-d'),
                'score' => 65.0,
                'matching_skills' => ['Architecture Logicielle', 'Bases de Données'],
                'missing_skills' => ['Message Queuing', 'Haute Disponibilité'],
                'summary' => 'Match moyen à 65.0% : profil solide en architecture et conception de données.',
            ],
        ];

        foreach ($opportunitiesData as $oppData) {
            $opp = Opportunite::updateOrCreate(
                ['deduplication_hash' => $oppData['deduplication_hash']],
                [
                    'titre' => $oppData['titre'],
                    'entreprise' => $oppData['entreprise'],
                    'localisation' => $oppData['localisation'],
                    'type_contrat' => $oppData['type_contrat'],
                    'description' => $oppData['description'],
                    'salaire_indicatif' => $oppData['salaire_indicatif'],
                    'teletravail' => $oppData['teletravail'],
                    'url_source' => $oppData['url_source'],
                    'date_publication' => $oppData['date_publication'],
                ]
            );

            // Création du match associé
            $match = JobMatch::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'opportunite_id' => $opp->id,
                ],
                [
                    'score_pertinence' => $oppData['score'],
                    'passed_hard_filters' => true,
                    'matching_skills' => $oppData['matching_skills'],
                    'missing_skills' => $oppData['missing_skills'],
                    'summary_explanation' => $oppData['summary'],
                    'statut' => $oppData['score'] > 90 ? 'favori' : 'nouveau',
                ]
            );

            // Création de candidatures de démonstration selon le score pour illustrer le suivi
            if ($oppData['score'] > 90) {
                Candidature::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'opportunite_id' => $opp->id,
                    ],
                    [
                        'match_id' => $match->id,
                        'objet_email' => "Candidature : {$opp->titre} - Candidat Démo",
                        'lettre_motivation' => "Candidat Démo\ncandidat.demo@jobmatch.ai | 06 00 00 00 00\n\nÀ l'attention de l'équipe de recrutement\n{$opp->entreprise}\n\nMadame, Monsieur,\n\nC'est avec un vif intérêt que je vous présente ma candidature au poste de {$opp->titre}.\n\nFort de 4 années d'expérience dans la conception de solutions logicielles performantes et fiables, j'ai développé une solide expertise en modélisation de données, architecture applicative et intégration de services web modulaires.\n\nRestant à votre entière disposition pour échanger lors d'un entretien,\n\nBien cordialement,\nCandidat Démo",
                        'cv_adaptation_tips' => [
                            "Mettez en valeur l'intitulé exact du poste dans l'en-tête de votre CV.",
                            "Détaillez vos réalisations en conception modulaire et performante.",
                            "Soulignez votre expérience en bases de données et APIs."
                        ],
                        'suggested_skills' => $oppData['matching_skills'],
                        'statut' => 'validee',
                        'mode_envoi' => 'email',
                    ]
                );
            } elseif ($oppData['score'] >= 80) {
                // Déposée et en attente de réponse (il y a 3 jours)
                Candidature::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'opportunite_id' => $opp->id,
                    ],
                    [
                        'match_id' => $match->id,
                        'objet_email' => "Candidature : {$opp->titre} - Candidat Démo",
                        'lettre_motivation' => "Candidat Démo\ncandidat.demo@jobmatch.ai | 06 00 00 00 00\n\nÀ l'attention des Ressources Humaines\n{$opp->entreprise}\n\nMadame, Monsieur,\n\nVotre offre pour le poste de {$opp->titre} a particulièrement retenu mon attention.\n\nMon parcours technique et mes compétences en gestion de flux de données correspondent étroitement aux défis de votre équipe.\n\nDans l'attente d'un échange approfondi,\n\nCordialement,\nCandidat Démo",
                        'cv_adaptation_tips' => [
                            "Mettre en exergue vos compétences de pilotage technique.",
                            "Ajouter des métriques chiffrées sur les volumes de données traités."
                        ],
                        'suggested_skills' => $oppData['matching_skills'],
                        'statut' => 'envoyee',
                        'date_envoi' => now()->subDays(3),
                        'notes_candidat' => 'Envoyé par email au responsable RH. En attente de premier retour.',
                        'mode_envoi' => 'email',
                    ]
                );
            } elseif ($oppData['score'] >= 75) {
                // Déposée avec entretien décroché (il y a 6 jours)
                Candidature::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'opportunite_id' => $opp->id,
                    ],
                    [
                        'match_id' => $match->id,
                        'objet_email' => "Candidature : {$opp->titre} - Candidat Démo",
                        'lettre_motivation' => "Candidat Démo\ncandidat.demo@jobmatch.ai | 06 00 00 00 00\n\nÀ l'attention de l'équipe technique\n{$opp->entreprise}\n\nMadame, Monsieur,\n\nDisponible pour intervenir sur votre projet, je vous soumets ma candidature pour la mission de {$opp->titre}.\n\nJe reste à votre écoute pour fixer un créneau d'échange.\n\nBien cordialement,\nCandidat Démo",
                        'cv_adaptation_tips' => [
                            "Mettre en avant les missions indépendantes déjà menées.",
                            "Préciser le TJM et la disponibilité immédiate."
                        ],
                        'suggested_skills' => $oppData['matching_skills'],
                        'statut' => 'entretien',
                        'date_envoi' => now()->subDays(6),
                        'notes_candidat' => 'Entretien visio prévu mardi prochain à 14h30 avec le directeur technique.',
                        'mode_envoi' => 'email',
                    ]
                );
            } else {
                // Brouillon en cours de rédaction
                Candidature::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'opportunite_id' => $opp->id,
                    ],
                    [
                        'match_id' => $match->id,
                        'objet_email' => "Candidature : {$opp->titre} - Candidat Démo",
                        'lettre_motivation' => "Candidat Démo\ncandidat.demo@jobmatch.ai\n\nMadame, Monsieur,\n\nJe prépare ma candidature pour le poste de {$opp->titre}...",
                        'cv_adaptation_tips' => ["Adapter les compétences clés."],
                        'suggested_skills' => $oppData['matching_skills'],
                        'statut' => 'brouillon',
                        'mode_envoi' => 'email',
                    ]
                );
            }
        }
    }
}
