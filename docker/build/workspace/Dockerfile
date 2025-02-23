FROM php:8.2.12-cli-alpine3.18

WORKDIR /var/www

RUN apk add --no-cache cronie sudo

RUN docker-php-ext-install pdo_mysql

USER root

COPY mycrontab /etc/crontabs/root
RUN chmod 600 /etc/crontabs/root
