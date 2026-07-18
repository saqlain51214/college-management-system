# syntax=docker/dockerfile:1

# ── Stage 1: build front-end assets (Vite/Tailwind) ──────────────────────────
FROM node:20-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
# Full source needed so Tailwind can scan blade templates for used classes
COPY . .
RUN npm run build

# ── Stage 2: PHP application ─────────────────────────────────────────────────
FROM php:8.3-cli

# System libraries needed to build the PHP extensions this app requires
RUN apt-get update && apt-get install -y \
        libicu-dev \
        libzip-dev \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        unzip \
        git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" intl gd zip bcmath pdo_mysql exif \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Install PHP dependencies first (better layer caching)
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts --no-progress

# Copy the rest of the application
COPY . /app

# Bring in the freshly built assets (overwrites any committed build)
COPY --from=assets /app/public/build /app/public/build

# Finalise autoload + publish framework/Filament assets (no DB needed)
RUN composer dump-autoload --optimize --no-interaction \
    && php artisan package:discover --ansi \
    && php artisan filament:assets

ENV PORT=8080
EXPOSE 8080

# Migrate + seed-once + storage:link, then serve the app
CMD php artisan app:deploy || true; \
    php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
