FROM composer:2 as builder

WORKDIR /app

COPY src/composer.json ./composer.json
COPY src/composer.lock ./composer.lock

RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist \
    --no-dev

FROM php:7.4-cli-alpine

WORKDIR /var/www/src/public

COPY . /var/www
COPY --from=builder /app/vendor /var/www/src/vendor

CMD php -S 0.0.0.0:80
EXPOSE 80