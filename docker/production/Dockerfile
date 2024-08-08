FROM dunglas/frankenphp

ENV SERVER_NAME=pevermeulen.blog

RUN apt-get update && apt-get install curl ca-certificates git nodejs npm -y

RUN docker-php-ext-install zip
RUN docker-php-ext-install exif

WORKDIR /app/
ENV WEB_DOCUMENT_ROOT /app/public

USER application
RUN git clone -b main https://github.com/rqpt/personal-blog .

RUN npm install
RUN npm run build

USER root
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

USER application
RUN composer install --no-interaction --optimize-autoloader

COPY .env.production .env
COPY php.ini /usr/local/etc/php

RUN php artisan optimize

RUN php artisan icons:cache

ENTRYPOINT ['php', 'artisan', 'octane:start']
