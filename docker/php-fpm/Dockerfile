FROM php:8.3-fpm

WORKDIR "/app"

RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    git \
    curl \
    openssl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    build-essential

RUN pecl install redis \
    && docker-php-ext-enable redis \
    && docker-php-ext-install gd pdo sockets

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer