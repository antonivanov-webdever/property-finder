FROM php:8.2-fpm

LABEL authors="Anton Ivanov"

RUN apt-get upgrade && apt-get update && apt-get install -y --no-install-recommends apt-utils \
    libpng-dev -y\
    libjpeg-dev -y\
    libfreetype6-dev -y\
    libzip-dev -y\
    unzip \
    git -y\
    nodejs \
    npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo_mysql curl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY --chown=www-data:www-data . /var/www/html

RUN composer install --no-dev --optimize-autoloader

RUN npm install && npm run build

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

CMD ["php-fpm"]
