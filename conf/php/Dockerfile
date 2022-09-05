FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
        openssl \
        git \
        zlib1g \
        zlib1g-dev \
        libfreetype6-dev \
        libssl-dev \
        libjpeg62-turbo-dev \
        libmagickwand-dev \
        libmcrypt-dev \
        libpng-dev \
        libicu-dev \
        libzip-dev \
        libpq-dev \
        zip \
        unzip \
        librabbitmq-dev \
        ffmpeg \
    ;

# Install PHP extensions
RUN docker-php-ext-install pdo_pgsql gd intl zip

RUN pecl install amqp && docker-php-ext-enable amqp

RUN pecl install xdebug && docker-php-ext-enable xdebug

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && mkdir /.composer/ \
    && chmod 777 /.composer/

