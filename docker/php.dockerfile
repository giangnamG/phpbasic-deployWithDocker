FROM php:fpm-alpine

RUN docker-php-ext-install pdo pdo_mysql
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli pdo_mysql

EXPOSE 9000