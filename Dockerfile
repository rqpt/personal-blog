FROM dunglas/frankenphp:latest-php8.3-alpine

ENV SERVER_NAME=pevermeulen.blog

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

RUN install-php-extensions \
  ctype \
  curl \
  dom \
  fileinfo \
  filter \
  hash \
  mbstring \
  openssl \
  pcre \
  pdo \
  session \
  pcntl \
  tokenizer \
  xml \
  pdo_mysql \
  zip

COPY . /app

RUN mkdir /app/resources/svg

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apk update && apk add nodejs npm vim

RUN npm install

RUN npm run build

RUN composer install --no-dev --no-interaction --optimize-autoloader

RUN php artisan key:generate

RUN php artisan optimize

RUN php artisan icons:cache
