FROM php:8.1-fpm-alpine

WORKDIR /var/www

RUN apk add --no-cache php8-bcmath\
    php8-phar \
    php8-iconv \
    php8-cli \
    php8-curl \
    php8-openssl \
    php8-mbstring \
    php8-tokenizer \
    php8-fileinfo \
    php8-json \
    php8-xml \
    php8-xmlwriter \
    php8-simplexml \
    php8-dom \
    php8-pdo_sqlite \
    php8-pecl-redis

RUN docker-php-ext-install pdo mysqli pdo_mysql pcntl exif opcache

RUN curl -sS https://getcomposer.org/installer -o composer-setup.php  \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer  \
    && rm -rf composer-setup.php

RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

COPY ../ /var/www

COPY --chown=www:www . /var/www

USER www

