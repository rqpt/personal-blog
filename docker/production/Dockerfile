FROM dunglas/frankenphp:latest-php8.3-alpine

ENV SERVER_NAME="pevermeulen.blog"

RUN apk update && apk add vim ca-certificates nodejs npm git

RUN install-php-extensions \
  pdo_mysql \
  pcntl \
  zip

WORKDIR /var/www

RUN rm -rf /html

RUN git clone -b main https://github.com/rqpt/personal-blog html
COPY php.ini /usr/local/etc/php/

WORKDIR /var/www/html

RUN npm install
RUN npm run build

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN mkdir resources/svg

RUN composer install --no-dev --no-interaction --optimize-autoloader

COPY .env.production .env

RUN php artisan key:generate
RUN php artisan optimize
RUN php artisan icons:cache

RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80 443 8000 443/udp

ENV SSL_CERT_FILE=/etc/ssl/certs/ca-certificates.crt
ENV SSL_CERT_DIR=/etc/ssl/certs

ENTRYPOINT ["php", "artisan", "octane:frankenphp"]
