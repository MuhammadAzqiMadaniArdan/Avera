# Base image PHP-FPM
FROM php:8.3-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    bash \
    git \
    zip \
    unzip \
    curl \
    oniguruma-dev \
    libzip-dev \
    postgresql-dev \
    autoconf \
    gcc \
    g++ \
    make \
    npm \
    redis \
    supervisor \
    shadow \
    libxml2-dev \
    icu-dev \
    zlib-dev \
    file \
    gcompat

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql mbstring bcmath zip xml intl opcache

# Enable Redis extension for PHP (optional, Laravel Predis can work without this)
RUN pecl install redis && docker-php-ext-enable redis

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy project files
COPY ../../ /var/www

# Set permissions for Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Install Laravel dependencies (production mode, no dev)
RUN composer install --prefer-dist --no-dev --no-autoloader

# Optimize autoloader
RUN composer dump-autoload -o

# Expose port 9000 for php-fpm
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
