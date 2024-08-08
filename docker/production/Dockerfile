FROM dunglas/frankenphp:latest-php8.3-alpine

ENV SERVER_NAME="http://"

RUN apk update && apk add vim git nodejs npm git

RUN install-php-extensions \
  pcntl \
  zip \
  exif

WORKDIR /

ENV WEB_DOCUMENT_ROOT /app/public

RUN rm -rf /app

RUN git clone -b main https://github.com/rqpt/personal-blog app

WORKDIR /app

RUN mkdir resources/svg

RUN npm install
RUN npm run build

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer install --no-interaction --optimize-autoloader

COPY .env.production .env
COPY php.ini /usr/local/etc/php

RUN php artisan optimize

RUN php artisan icons:cache

ENTRYPOINT [php, artisan, octane:frankenphp]
