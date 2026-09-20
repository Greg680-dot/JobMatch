# JobMatch AI 🚀
**Plateforme intelligente de recherche d'opportunités et d'assistance à la candidature**

[![Version](https://img.shields.io/badge/version-1.1-blue.svg)](docs/cahier_des_charges_v1_1.md)
[![Architecture](https://img.shields.io/badge/architecture-Laravel_11_%2B_Python_FastAPI-green.svg)]()
[![Database](https://img.shields.io/badge/database-PostgreSQL_16_%2B_pgvector-purple.svg)]()

JobMatch AI automatise la découverte d'offres d'emploi, de stages, de bourses et de missions freelance, analyse la compatibilité sémantique avec votre profil (*matching vectoriel*) et rédige des candidatures personnalisées à fort impact, tout en maintenant l'humain au centre de chaque décision.

---

## 🏗️ Architecture du Projet

```
jobmatch-ai/
├── backend/                  # Application Laravel 11 + Vue.js 3 (Inertia.js)
│   ├── app/                  # Contrôleurs, Modèles Eloquent, Services IA
│   ├── database/             # Migrations et Seeders
│   └── resources/            # Vues et composants Vue 3
├── ai-service/               # Microservice Python (FastAPI + Ingestion + Matching)
│   ├── app/api/              # Endpoints REST (/parse-cv, /matching, /generate-letter)
│   ├── app/collectors/       # Ingestion d'offres (APIs ouvertes, RSS, scrapers)
│   ├── app/matching/         # Moteur vectoriel de calcul de score (pgvector / Cosine)
│   └── app/llm/              # Extraction CV et rédaction personnalisée
├── docker/                   # Orchestration Docker pour VPS de production
│   └── docker-compose.yml    # Web + AI Worker + PostgreSQL pgvector + Redis
└── docs/                     # Spécifications & Cahier des charges v1.1
```

---

## ⚡ Démarrage Rapide en Développement Local

### 1. Lancer le microservice Python IA
```bash
cd ai-service
pip install -r requirements.txt
python main.py
```
> L'API démarre sur `http://127.0.0.1:8000`.  
> Documentation interactive Swagger : `http://127.0.0.1:8000/docs`

### 2. Lancer l'application web Laravel
```bash
cd backend
composer install
npm install
php artisan migrate
npm run dev
php artisan serve --port=8088
```
> L'application est accessible sur `http://127.0.0.1:8088`.

---

## 🐳 Déploiement en Production (VPS Linux avec Docker)

Le fichier `docker/docker-compose.yml` orchestre tous les conteneurs :
```bash
cd docker
docker compose up -d --build
```
Les conteneurs lancés comprennent :
1. **`jobmatch_postgres`** : PostgreSQL 16 avec extension `pgvector` activée.
2. **`jobmatch_redis`** : Redis 7 pour les files d'attente asynchrones.
3. **`jobmatch_ai_service`** : Microservice Python FastAPI.
4. **`jobmatch_backend`** : Application Laravel 11 + Nginx.

---

## 📄 Documentation
Consultez le Cahier des Charges complet dans [docs/cahier_des_charges_v1_1.md](docs/cahier_des_charges_v1_1.md) ou au format PDF [JobMatch_AI_Cahier_des_charges_v1.1.pdf](JobMatch_AI_Cahier_des_charges_v1.1.pdf).
