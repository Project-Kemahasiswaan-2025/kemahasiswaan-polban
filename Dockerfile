FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
        libzip-dev \
        unzip \
        git \
        libpng-dev \
        libjpeg-dev \
    libicu-dev \
    && docker-php-ext-install pdo_mysql zip exif pcntl gd

RUN docker-php-ext-install intl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY docker/php/local.ini /usr/local/etc/php/conf.d/local.ini

WORKDIR /var/www/html
