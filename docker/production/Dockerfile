FROM dunglas/frankenphp

ENV SERVER_NAME=pevermeulen.blog

RUN apt-get update && apt-get install curl ca-certificates git unzip libzip-dev nodejs npm vim -y

RUN docker-php-ext-install zip
RUN docker-php-ext-install exif

WORKDIR /

ENV WEB_DOCUMENT_ROOT /app/public

RUN rm -rf /app

RUN git clone -b main https://github.com/rqpt/personal-blog app

WORKDIR /app

RUN npm install
RUN npm run build

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer install --no-interaction --optimize-autoloader

COPY .env.production .env
COPY php.ini /usr/local/etc/php

RUN php artisan optimize

RUN php artisan icons:cache

ENTRYPOINT ['php', 'artisan', 'octane:start']
