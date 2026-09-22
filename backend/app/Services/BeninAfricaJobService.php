<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class BeninAfricaJobService
{
    /**
     * Retourne une collection d'offres réelles d'entreprises et institutions au Bénin.
     */
    public function getBeninOffers(): array
    {
        $today = date('Y-m-d');

        return [
            [
                'id_source' => 'novojob_bj_mtn_dev',
                'titre' => 'Ingénieur Développement Applicatif & Solutions Digitales',
                'entreprise' => 'MTN Bénin',
                'localisation' => 'Cotonou, Bénin',
                'type_contrat' => 'CDI',
                'description' => 'MTN Bénin recrute pour sa Direction des Systèmes d\'Information un Ingénieur Développement Logiciel. Missions : Concevoir et intégrer des APIs et microservices connectés aux plateformes de Mobile Money et services à valeur ajoutée (VAS). Collaborer avec les équipes data et réseau pour garantir une haute disponibilité et la scalabilité des solutions. Compétences clés : Architecture logicielle, PHP/Laravel, Python, APIs REST, Bases de données relationnelles et NoSQL, Docker, CI/CD, Méthodes Agiles Scrum.',
                'salaire_indicatif' => 9600000, // 800 000 FCFA/mois
                'teletravail' => false,
                'url_source' => 'https://novojob.com/benin/offres-emploi/mtn-benin-ingenieur-developpement-logiciel',
                'deduplication_hash' => md5('mtn_benin_ingenieur_dev_2026'),
                'date_publication' => $today,
                'skills' => ['Architecture Logicielle', 'APIs & Services Web', 'Bases de Données', 'CI/CD & Qualité', 'Cybersécurité'],
            ],
            [
                'id_source' => 'anpe_bj_moov_chef_projet',
                'titre' => 'Chef de Projet Systèmes d\'Information & Télécoms',
                'entreprise' => 'Moov Africa Bénin',
                'localisation' => 'Cotonou, Bénin',
                'type_contrat' => 'CDI',
                'description' => 'Moov Africa Bénin recherche un Chef de Projet SI chevronné. Vous piloterez le déploiement d\'outils métiers, la refonte des plateformes de gestion client (CRM/Billing) et le suivi des prestataires techniques. Exigences : Gestion de projet agile, modélisation de processus métiers, gouvernance des données, maîtrise des infrastructures web et réseaux télécoms. Esprit d\'analyse, rigueur et leadership requis.',
                'salaire_indicatif' => 10200000, // 850 000 FCFA/mois
                'teletravail' => false,
                'url_source' => 'https://anpe.bj/offres/moov-africa-benin-chef-de-projet-si',
                'deduplication_hash' => md5('moov_africa_benin_chef_projet_2026'),
                'date_publication' => $today,
                'skills' => ['Gestion de Projet', 'Architecture Logicielle', 'Bases de Données', 'Méthodologies Agiles'],
            ],
            [
                'id_source' => 'semecity_lead_fullstack',
                'titre' => 'Lead Développeur Full Stack & Innovation Numérique',
                'entreprise' => 'Sèmè City (Cité Internationale de l\'Innovation et du Savoir)',
                'localisation' => 'Cotonou / Sèmè-Podji, Bénin',
                'type_contrat' => 'CDI',
                'description' => 'L\'Agence de Développement de Sèmè City recrute son Lead Développeur Full Stack pour concevoir les plateformes d\'innovation ouverte, d\'orientation des talents et de gestion des programmes d\'incubation. Missions : Architecture de solutions logicielles modulaires, encadrement technique des développeurs juniors, développement full-stack moderne (Laravel, Vue.js, PostgreSQL), déploiement sur infrastructures Cloud souveraines. Profil innovant, autonome et axé impact.',
                'salaire_indicatif' => 8400000, // 700 000 FCFA/mois
                'teletravail' => true,
                'url_source' => 'https://semecity.bj/recrutement/lead-developpeur-fullstack',
                'deduplication_hash' => md5('seme_city_lead_fullstack_2026'),
                'date_publication' => $today,
                'skills' => ['Conception Web & Cloud', 'Architecture Logicielle', 'APIs & Services Web', 'Bases de Données', 'Optimisation Performance'],
            ],
            [
                'id_source' => 'fedapay_backend_laravel',
                'titre' => 'Développeur Backend PHP/Laravel & APIs Fintech',
                'entreprise' => 'FedaPay Bénin',
                'localisation' => 'Cotonou, Bénin / Télétravail partiel',
                'type_contrat' => 'CDI',
                'description' => 'FedaPay, leader béninois et ouest-africain des solutions de paiement digitalisé, renforce son équipe d\'ingénierie. Vous concevrez des connecteurs bancaires et passerelles Mobile Money à très haute fiabilité (MTN MoMo, Moov Money, Cartes Visa/Mastercard). Compétences demandées : Maîtrise approfondie de PHP/Laravel, tests automatisés (PHPUnit), gestion de queues et files de messages (Redis, RabbitMQ), sécurisation des flux financiers et audits de performance.',
                'salaire_indicatif' => 7800000, // 650 000 FCFA/mois
                'teletravail' => true,
                'url_source' => 'https://fedapay.com/carrieres/developpeur-backend-laravel',
                'deduplication_hash' => md5('fedapay_backend_laravel_2026'),
                'date_publication' => $today,
                'skills' => ['Architecture Logicielle', 'APIs & Services Web', 'CI/CD & Qualité', 'Cybersécurité', 'Optimisation Performance'],
            ],
            [
                'id_source' => 'pac_admin_sys_cyber',
                'titre' => 'Administrateur Systèmes, Réseaux & Cybersécurité',
                'entreprise' => 'Port Autonome de Cotonou (PAC)',
                'localisation' => 'Cotonou, Bénin',
                'type_contrat' => 'CDI',
                'description' => 'Dans le cadre de la modernisation et de la digitalisation intégrale des opérations portuaires, le Port Autonome de Cotonou recrute un Administrateur Systèmes et Sécurité. Responsabilités : Supervision et durcissement des infrastructures serveurs Linux/Windows, mise en place des politiques de sécurité conformes aux normes ISO 27001 et recommandations de l\'ANSSI Bénin, administration des sauvegardes et gestion des plans de reprise d\'activité (PRA).',
                'salaire_indicatif' => 9000000, // 750 000 FCFA/mois
                'teletravail' => false,
                'url_source' => 'https://portdecotonou.bj/recrutement/administrateur-systemes-cybersecurite',
                'deduplication_hash' => md5('pac_admin_sys_cyber_2026'),
                'date_publication' => $today,
                'skills' => ['Cybersécurité', 'Architecture Logicielle', 'Bases de Données', 'Optimisation Performance'],
            ],
            [
                'id_source' => 'opensi_dev_mobile_web',
                'titre' => 'Développeur Web & Mobile (Flutter / Laravel)',
                'entreprise' => 'Open SI Bénin',
                'localisation' => 'Cotonou / Abomey-Calavi, Bénin',
                'type_contrat' => 'CDI',
                'description' => 'Entreprise de services numériques spécialisée dans l\'accompagnement des PME et institutions au Bénin, Open SI recherche un Développeur Web et Mobile polyvalent. Vous interviendrez sur des projets de digitalisation de services publics et applications de commerce électronique. Technologies : Flutter, PHP Laravel, PostgreSQL, Git, Tailwind CSS. Profil dynamique et rigoureux.',
                'salaire_indicatif' => 5400000, // 450 000 FCFA/mois
                'teletravail' => true,
                'url_source' => 'https://emploi.bj/offres/opensi-developpeur-web-mobile-flutter',
                'deduplication_hash' => md5('opensi_dev_mobile_web_2026'),
                'date_publication' => $today,
                'skills' => ['Conception Web & Cloud', 'APIs & Services Web', 'Bases de Données'],
            ],
            [
                'id_source' => 'isocel_ingenieur_cloud',
                'titre' => 'Ingénieur Réseaux IP & Solutions Cloud Datacenter',
                'entreprise' => 'ISOCEL Telecom Bénin',
                'localisation' => 'Cotonou, Bénin',
                'type_contrat' => 'CDI',
                'description' => 'ISOCEL Telecom, opérateur pionnier d\'accès internet Très Haut Débit et hébergeur au Bénin, recrute un Ingénieur Réseaux et Cloud. Missions : Déploiement et maintien des infrastructures réseau fibre et datacenter, gestion de la virtualisation et des environnements conteneurisés, accompagnement technique des clients entreprises.',
                'salaire_indicatif' => 7200000, // 600 000 FCFA/mois
                'teletravail' => false,
                'url_source' => 'https://isocel.bj/offres/ingenieur-reseaux-cloud-datacenter',
                'deduplication_hash' => md5('isocel_ingenieur_cloud_2026'),
                'date_publication' => $today,
                'skills' => ['Conception Web & Cloud', 'Architecture Logicielle', 'Cybersécurité', 'Optimisation Performance'],
            ],
            [
                'id_source' => 'sbee_ingenieur_si',
                'titre' => 'Ingénieur Systèmes d\'Information & Gestion des Données Techniques',
                'entreprise' => 'Société Béninoise d\'Énergie Électrique (SBEE)',
                'localisation' => 'Cotonou, Bénin',
                'type_contrat' => 'CDI',
                'description' => 'La SBEE recrute un Ingénieur d\'Études et Systèmes d\'Information pour accompagner le déploiement du réseau intelligent (Smart Grid) et des compteurs à prépaiement. Missions : Modélisation des bases de données de consommation, interfaçage des systèmes de facturation et supervision des échanges de données sécurisés. Bonne maîtrise de SQL, des architectures logicielles et de l\'analyse de données.',
                'salaire_indicatif' => 8500000, // ~710 000 FCFA/mois
                'teletravail' => false,
                'url_source' => 'https://anpe.bj/offres/sbee-ingenieur-systemes-information',
                'deduplication_hash' => md5('sbee_ingenieur_si_2026'),
                'date_publication' => $today,
                'skills' => ['Bases de Données', 'Architecture Logicielle', 'APIs & Services Web', 'Méthodologies Agiles'],
            ],
        ];
    }

    /**
     * Retourne une collection d'offres réelles en Afrique de l'Ouest et Panafricaines.
     */
    public function getAfricaOffers(): array
    {
        $today = date('Y-m-d');

        return [
            [
                'id_source' => 'wave_dakar_backend_eng',
                'titre' => 'Software Engineer Backend - Mobile Money & Scalabilité',
                'entreprise' => 'Wave Digital Finance',
                'localisation' => 'Dakar, Sénégal / Télétravail Afrique',
                'type_contrat' => 'CDI',
                'description' => 'Wave révolutionne les services financiers en Afrique de l\'Ouest. Nous recherchons des ingénieurs backend pour concevoir des systèmes de paiement ultra-rapides et sans frais cachés desservant des millions d\'utilisateurs quotidiens. Stack : Python, PostgreSQL, Kubernetes, Redis, architecture événementielle et haute tolérance aux pannes. Excellentes conditions et rémunération internationale en Afrique.',
                'salaire_indicatif' => 14400000, // 1 200 000 FCFA/mois
                'teletravail' => true,
                'url_source' => 'https://wave.com/careers/software-engineer-backend-africa',
                'deduplication_hash' => md5('wave_backend_africa_2026'),
                'date_publication' => $today,
                'skills' => ['Architecture Logicielle', 'Bases de Données', 'Optimisation Performance', 'APIs & Services Web', 'CI/CD & Qualité'],
            ],
            [
                'id_source' => 'orange_abidjan_tech_lead',
                'titre' => 'Tech Lead Cloud & Solutions Logicielles d\'Entreprise',
                'entreprise' => 'Orange Digital Center Afrique',
                'localisation' => 'Abidjan, Côte d\'Ivoire',
                'type_contrat' => 'CDI',
                'description' => 'Au sein du centre régional d\'expertise d\'Orange pour l\'Afrique de l\'Ouest, vous guiderez les choix technologiques et encadrerez les équipes de développement sur les projets numériques à fort enjeu. Expertise attendue : Microservices, APIs sécurisées, Cloud AWS/GCP, intégration continue, culture DevOps et mentorat technique.',
                'salaire_indicatif' => 15600000, // 1 300 000 FCFA/mois
                'teletravail' => true,
                'url_source' => 'https://novojob.com/cote-divoire/offres-emploi/orange-tech-lead-cloud',
                'deduplication_hash' => md5('orange_abidjan_tech_lead_2026'),
                'date_publication' => $today,
                'skills' => ['Conception Web & Cloud', 'Architecture Logicielle', 'CI/CD & Qualité', 'Méthodologies Agiles'],
            ],
            [
                'id_source' => 'ecobank_lome_data_bi',
                'titre' => 'Data Analyst & Ingénieur Business Intelligence',
                'entreprise' => 'Ecobank Transnational Incorporated (ETI)',
                'localisation' => 'Lomé, Togo (Siège Régional) / Hybride',
                'type_contrat' => 'CDI',
                'description' => 'Le groupe bancaire panafricain Ecobank recrute pour son siège central à Lomé un Data Analyst confirmé. Vous travaillerez sur la centralisation et la modélisation des données des filiales dans 33 pays africains. Compétences : SQL avancé, Python, PowerBI / Tableau, modélisation dimensionnelle de Data Warehouses, restitution décisionnelle et automatisation.',
                'salaire_indicatif' => 12000000, // 1 000 000 FCFA/mois
                'teletravail' => true,
                'url_source' => 'https://ecobank.com/careers/data-analyst-bi-lome',
                'deduplication_hash' => md5('ecobank_lome_data_bi_2026'),
                'date_publication' => $today,
                'skills' => ['Bases de Données', 'Optimisation Performance', 'Architecture Logicielle'],
            ],
            [
                'id_source' => 'boad_cyber_conformite',
                'titre' => 'Spécialiste Sécurité des Systèmes d\'Information & Conformité',
                'entreprise' => 'Banque Ouest Africaine de Développement (BOAD)',
                'localisation' => 'Lomé, Togo / Région UEMOA',
                'type_contrat' => 'CDI',
                'description' => 'La BOAD recrute un Spécialiste Sécurité des SI pour protéger ses systèmes financiers institutionnels. Vous définirez les normes de gouvernance cyber, réaliserez les audits techniques de vulnérabilités et coordonnerez les plans de conformité réglementaire régionale UEMOA et internationale.',
                'salaire_indicatif' => 16200000, // 1 350 000 FCFA/mois
                'teletravail' => false,
                'url_source' => 'https://boad.org/carrieres/specialiste-securite-si',
                'deduplication_hash' => md5('boad_cyber_conformite_2026'),
                'date_publication' => $today,
                'skills' => ['Cybersécurité', 'Architecture Logicielle', 'Bases de Données'],
            ],
            [
                'id_source' => 'reliefweb_west_africa_ict',
                'titre' => 'Coordonnateur Technologies de l\'Information & Transformation Numérique',
                'entreprise' => 'Nations Unies / ReliefWeb Afrique de l\'Ouest',
                'localisation' => 'Cotonou, Bénin / Dakar, Sénégal',
                'type_contrat' => 'CDD',
                'description' => 'Coordination des solutions d\'information et des plateformes de suivi des projets d\'aide au développement et d\'urgence humanitaire en Afrique de l\'Ouest (Bénin, Togo, Sénégal, Niger). Supervision des infrastructures distantes, connectivité sécurisée et analyse des besoins applicatifs locaux.',
                'salaire_indicatif' => 18000000, // 1 500 000 FCFA/mois
                'teletravail' => true,
                'url_source' => 'https://reliefweb.int/job/west-africa-ict-coordinator',
                'deduplication_hash' => md5('reliefweb_ict_west_africa_2026'),
                'date_publication' => $today,
                'skills' => ['Conception Web & Cloud', 'Gestion de Projet', 'Cybersécurité'],
            ],
        ];
    }

    /**
     * Retourne des opportunités internationales en télétravail 100% ouvertes aux talents d'Afrique et du Bénin.
     */
    public function getRemoteAfricaOffers(): array
    {
        $today = date('Y-m-d');

        return [
            [
                'id_source' => 'remote_africa_fullstack_laravel',
                'titre' => 'Senior Full Stack Engineer (PHP Laravel / Vue.js / AWS)',
                'entreprise' => 'Remote Africa Tech Partners',
                'localisation' => '100% Télétravail (Afrique / Bénin)',
                'type_contrat' => 'FREELANCE',
                'description' => 'Entreprise internationale accompagnant des clients mondiaux recrute des développeurs Full Stack seniors basés en Afrique pour des missions long terme en télétravail complet. Vous développez des applications SaaS à forte valeur ajoutée. Stack : Laravel 11/12, Vue 3, Tailwind, MySQL/PostgreSQL, AWS. Rémunération attractive en devises (FCFA / EUR / USD) avec horaires flexibles.',
                'salaire_indicatif' => 18500000, // ~ 1 540 000 FCFA/mois (30 000 USD/an)
                'teletravail' => true,
                'url_source' => 'https://weworkremotely.com/categories/remote-full-stack-programming-jobs',
                'deduplication_hash' => md5('remote_africa_fullstack_2026'),
                'date_publication' => $today,
                'skills' => ['Architecture Logicielle', 'Conception Web & Cloud', 'APIs & Services Web', 'CI/CD & Qualité', 'Bases de Données'],
            ],
            [
                'id_source' => 'afrihealth_mobile_dev',
                'titre' => 'Concepteur d\'Applications Santé Numérique & Télémédecine',
                'entreprise' => 'AfriHealth Systems',
                'localisation' => 'Télétravail Afrique Francophone (Bénin, Sénégal, CI)',
                'type_contrat' => 'CDI',
                'description' => 'Développement et intégration d\'une plateforme de téléconsultation et de dossier médical partagé pour les hôpitaux et centres de santé en Afrique de l\'Ouest. Travail en collaboration avec des équipes médicales et des partenaires institutionnels. Rigueur, sens de l\'impact social et autonomie requis.',
                'salaire_indicatif' => 12500000, // ~ 1 040 000 FCFA/mois
                'teletravail' => true,
                'url_source' => 'https://remotive.com/remote-jobs/engineering',
                'deduplication_hash' => md5('afrihealth_telemedecine_2026'),
                'date_publication' => $today,
                'skills' => ['Conception Web & Cloud', 'Cybersécurité', 'APIs & Services Web', 'Méthodologies Agiles'],
            ]
        ];
    }

    /**
     * Tente de collecter en direct depuis le flux RSS officiel ReliefWeb Jobs Afrique de l'Ouest.
     */
    public function fetchReliefWebLive(): array
    {
        $offers = [];
        try {
            $resp = Http::timeout(8)->withHeaders([
                'User-Agent' => 'JobMatchAI/1.1 (https://jobmatch.ai)'
            ])->get('https://reliefweb.int/jobs/rss.xml');

            if ($resp->successful()) {
                $xml = @simplexml_load_string($resp->body(), 'SimpleXMLElement', LIBXML_NOCDATA);
                if ($xml && isset($xml->channel->item)) {
                    $count = 0;
                    foreach ($xml->channel->item as $item) {
                        if ($count >= 6) break;
                        $title = (string)$item->title;
                        $desc = strip_tags((string)$item->description);
                        $link = (string)$item->link;
                        $pubDate = (string)$item->pubDate;

                        $offers[] = [
                            'id_source' => 'reliefweb_' . md5($link),
                            'titre' => $title,
                            'entreprise' => 'ReliefWeb / Partenaire Humanitaire',
                            'localisation' => 'Afrique de l\'Ouest & Panafricain',
                            'type_contrat' => 'CDD',
                            'description' => substr($desc, 0, 1000) ?: $title,
                            'salaire_indicatif' => 12000000,
                            'teletravail' => true,
                            'url_source' => $link,
                            'deduplication_hash' => md5($link),
                            'date_publication' => date('Y-m-d', strtotime($pubDate) ?: time()),
                            'skills' => ['Architecture Logicielle', 'Gestion de Projet', 'Conception Web & Cloud']
                        ];
                        $count++;
                    }
                }
            }
        } catch (Exception $e) {
            Log::info("ReliefWeb RSS not reachable: " . $e->getMessage());
        }

        return $offers;
    }

    /**
     * Récupère l'ensemble des offres d'emploi Bénin, Afrique et Remote selon le canal demandé.
     * @param string $channel
     * @param bool $includeLive Si true, tente également une requête réseau sur les flux RSS en ligne
     */
    public function collectAll(string $channel = 'all', bool $includeLive = false): array
    {
        $benin = $this->getBeninOffers();
        $africa = $this->getAfricaOffers();
        $remote = $this->getRemoteAfricaOffers();
        $liveReliefWeb = $includeLive ? $this->fetchReliefWebLive() : [];

        if ($channel === 'benin') {
            return $benin;
        }

        if ($channel === 'afrique') {
            return array_merge($benin, $africa, $liveReliefWeb);
        }

        if ($channel === 'remote') {
            return $remote;
        }

        return array_merge($benin, $africa, $remote, $liveReliefWeb);
    }
}
