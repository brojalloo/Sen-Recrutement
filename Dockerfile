# --- Étape 1 : compilation des assets front (Vite + Tailwind) ---
FROM node:22-bookworm-slim AS assets

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY vite.config.js ./
COPY resources ./resources
RUN npm run build

# --- Étape 2 : application PHP ---
# Laravel 12 exige PHP >= 8.2 (voir composer.json).
FROM php:8.3-cli

RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo_mysql zip mbstring exif pcntl bcmath xml gd \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction --no-scripts --optimize-autoloader

COPY . ./
COPY --from=assets /app/public/build ./public/build

RUN composer dump-autoload --optimize --no-dev \
    && php artisan storage:link \
    && chown -R www-data:www-data storage bootstrap/cache

USER www-data

EXPOSE 8000

# APP_KEY doit être fourni par l'environnement. Le générer ici invaliderait
# toutes les sessions et rendrait illisible toute donnée chiffrée à chaque
# redémarrage du conteneur.
CMD ["sh", "-c", "php artisan migrate --force && php artisan config:cache && php artisan route:cache && php artisan serve --host=0.0.0.0 --port=8000"]
