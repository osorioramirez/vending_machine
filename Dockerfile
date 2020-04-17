FROM php:7.4-cli-alpine

#RUN apt-get update -y && apt-get install -y libmcrypt-dev openssl git zip unzip
RUN apk add --no-cache \
        $PHPIZE_DEPS \
        libmcrypt-dev \
        openssl \
        git \
        zip \
        unzip \
    ;

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer global require hirak/prestissimo

# Redis
RUN pecl install redis
RUN docker-php-ext-enable redis

WORKDIR /srv/app
COPY . .

RUN composer install
