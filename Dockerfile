FROM php:8.3-cli-alpine

WORKDIR /app

# Dépendances système & extensions PHP pour Laravel et SQLite
RUN apk add --no-cache \
    sqlite-dev \
    sqlite-libs \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    oniguruma-dev \
    && docker-php-ext-install pdo pdo_sqlite zip bcmath mbstring

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copie du code backend Laravel
COPY backend/ .

# Installation des dépendances PHP optimisées pour la production
RUN composer install --no-dev --optimize-autoloader --no-interaction

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