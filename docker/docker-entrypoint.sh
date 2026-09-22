#!/bin/sh
set -e

echo "=== Démarrage de JobMatch AI sur le Cloud ==="

# 1. Vérification du fichier .env
if [ ! -f .env ]; then
    if [ -f .env.example ]; then
        cp .env.example .env
    else
        touch .env
    fi
fi

# 2. Génération de clé d'application si absente
if [ -z "$APP_KEY" ] && ! grep -q "^APP_KEY=base64:" .env; then
    echo "Génération de la clé d'application..."
    php artisan key:generate --force
fi

# 3. Préparation du stockage et SQLite
mkdir -p database storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
touch database/database.sqlite
chmod -R 777 database storage bootstrap/cache 2>/dev/null || true

# 4. Découverte des packages
php artisan package:discover --ansi

# 5. Migrations & Données de démonstration
echo "Exécution des migrations et chargement des données de test..."
php artisan migrate --force
php artisan db:seed --class=JobMatchSeeder --force

# 6. Nettoyage des caches Laravel
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 7. Démarrage du serveur web sur le port dynamique du Cloud (Render / Railway)
PORT="${PORT:-8080}"
echo "JobMatch AI écoute sur le port ${PORT} (24h/24 & 7j/7)..."
exec php artisan serve --host=0.0.0.0 --port="${PORT}"