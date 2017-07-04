FROM php:5.6-apache

COPY ./init-container.sh /init-container.sh
COPY ./init-project.sh /init-project.sh

COPY . /var/www/html

RUN apt-get update && apt-get install -y \
        wget \
        libicu-dev \
        libzip-dev \
        libjpeg-dev \
        libpng-dev \
        git-core \
        php5-sqlite \
        php5-intl \
        php5-mysql \
        php5-curl \
        php5-dev \
        acl \
        mysql-client && \
        docker-php-ext-configure gd --with-jpeg-dir=/usr/include/  &&  \
        docker-php-ext-install gd

RUN docker-php-ext-install -j$(nproc) pdo_mysql
RUN ["/init-container.sh"]

WORKDIR /var/www/html

EXPOSE 80