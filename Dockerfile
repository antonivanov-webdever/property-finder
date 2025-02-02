FROM php:8.2-fpm

LABEL authors="Anton Ivanov"

RUN apt-get upgrade && apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY --chown=www-data:www-data . /var/www/html

RUN composer install --no-dev --optimize-autoloader
RUN chmod -R 775 storage bootstrap/cache

CMD ["php-fpm"]
