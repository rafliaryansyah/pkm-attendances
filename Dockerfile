# Stage 1: Build Assets
FROM node:20-alpine AS assets

WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY . .
RUN npm run build

# Stage 2: Build PHP Application
FROM php:8.3-fpm-alpine AS php

WORKDIR /var/www/html

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    oniguruma-dev \
    postgresql-dev \
    icu-dev \
    bash \
    netcat-openbsd \
    gettext \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        pdo_pgsql \
        gd \
        zip \
        mbstring \
        intl \
        bcmath

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . .

# Copy built assets from assets stage
COPY --from=assets /app/public/build ./public/build

# Install PHP dependencies (PHP 8.3 matches lock file requirements)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Configure Nginx (template will be processed by entrypoint)
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/default.conf.template /etc/nginx/http.d/default.conf.template

# Configure PHP-FPM
RUN echo "clear_env = no" >> /usr/local/etc/php-fpm.d/www.conf

# Configure Supervisor
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Entrypoint script for Railway
COPY docker/entrypoint-railway.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Railway uses dynamic PORT
ENV PORT=8080
EXPOSE $PORT

ENTRYPOINT ["/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
