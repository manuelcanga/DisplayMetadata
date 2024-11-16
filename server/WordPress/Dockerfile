ARG WORDPRESS_VERSION
ARG PHP_VERSION

FROM wordpress:${WORDPRESS_VERSION}-php${PHP_VERSION}-apache
MAINTAINER Manuel Canga <holamundo@manuelcanga.dev>

# Install php modules
#RUN apt-get install php-xdebug &&  docker-php-ext-enable xdebug
RUN apt-get update && apt-get install -y zlib1g-dev libicu-dev g++
RUN docker-php-ext-configure intl &&  docker-php-ext-install intl
RUN docker-php-ext-install mysqli
RUN apt update \
    && apt-get install -y libonig-dev libfreetype6-dev libjpeg62-turbo-dev libpng-dev \
    && docker-php-ext-install -j$(nproc) intl pdo_mysql gettext bcmath mbstring exif

# Add sudo in order to run wp-cli as the www-data user
RUN apt-get update && apt-get install -y sudo less default-mysql-client

# Install basic tools
RUN apt-get update && apt-get install -y wget
RUN apt-get install -y vim nano
RUN apt-get install -y git

COPY ./php/php.ini /usr/local/etc/php/

EXPOSE 80