ARG PHP_VERSION=8.0

FROM php:${PHP_VERSION}-fpm-alpine

RUN apk update && apk add --no-cache \
    git \
    mc

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /usr/local/app

RUN chown www-data:www-data /usr/local/app

RUN composer self-update --2
COPY composer.* ./

USER www-data
