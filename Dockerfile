FROM php:8.1.8-fpm-alpine

WORKDIR /
RUN apk update
RUN apk add bash
RUN apk add curl

RUN apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

# INSTALL COMPOSER
RUN curl -s https://getcomposer.org/installer | php
RUN alias composer='php /composer.phar'

RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.alpine.sh' | bash
RUN apk add symfony-cli


WORKDIR /app

ADD . .

RUN /composer.phar install

EXPOSE 80

ENTRYPOINT ["/app/_ci/build/coding-challenge/entrypoint.sh"]
