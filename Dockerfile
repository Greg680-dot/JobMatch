FROM php:8.3-cli-bookworm

WORKDIR /app

# Dépendances système & extensions PHP pour Laravel et SQLite
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite mbstring zip bcmath \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Installation de Composer
COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer

# Autoriser Composer en root dans le conteneur
ENV COMPOSER_ALLOW_SUPERUSER=1

# Copie du code backend Laravel
COPY backend/ .

# Installation des dépendances sans scripts au build (évite l'erreur exit code 2)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Préparation des dossiers d'écriture
RUN mkdir -p database storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && touch database/database.sqlite \
    && chmod -R 777 database storage bootstrap/cache

# Script d'amorçage
COPY docker/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Port par défaut (sera surchargé automatiquement par $PORT sur Render / Railway)
EXPOSE 8080

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]