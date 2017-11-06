FROM composer:1.5 as composer
FROM php:7.1-apache

COPY --from=composer /usr/bin/composer /usr/bin/composer

COPY ./init-container.sh /init-container.sh
COPY ./init-project.sh /init-project.sh

COPY . /var/www/html

# replace shell with bash so we can source files
RUN rm /bin/sh && ln -s /bin/bash /bin/sh

RUN apt-get update && apt-get install -y \
        wget \
        libicu-dev \
        libzip-dev \
        libjpeg-dev \
        libpng-dev \
        git-core \
        acl \
        # begin libs for wkhtmltopdf
        libxrender1 libfontconfig libxtst6 xz-utils \
        # end libs for wkhtmltopdf
        mysql-client

RUN curl "https://github.com/wkhtmltopdf/wkhtmltopdf/releases/download/0.12.4/wkhtmltox-0.12.4_linux-generic-amd64.tar.xz" -L -o "wkhtmltopdf.tar.xz"
RUN tar Jxvf wkhtmltopdf.tar.xz
RUN mv wkhtmltox/bin/wkhtmltopdf /usr/bin/wkhtmltopdf
RUN mv wkhtmltox/bin/wkhtmltoimage /usr/bin/wkhtmltoimage

RUN docker-php-ext-configure opcache --enable-opcache && \
        docker-php-ext-install opcache && \
        pecl install apcu && \
        docker-php-ext-enable apcu && \
        docker-php-ext-configure gd --with-jpeg-dir=/usr/include/ && \
        docker-php-ext-install gd && \
        docker-php-ext-configure intl && \
        docker-php-ext-install intl

# nvm environment variables
ENV NVM_DIR /usr/local/nvm
ENV NODE_VERSION 8.1.3

# install nvm
# https://github.com/creationix/nvm#install-script
RUN curl --silent -o- https://raw.githubusercontent.com/creationix/nvm/v0.33.2/install.sh | bash

# install node and npm
RUN source $NVM_DIR/nvm.sh \
    && nvm install $NODE_VERSION \
    && nvm alias default $NODE_VERSION \
    && nvm use default

# add node and npm to path so the commands are available
ENV NODE_PATH $NVM_DIR/v$NODE_VERSION/lib/node_modules
ENV PATH $NVM_DIR/versions/node/v$NODE_VERSION/bin:$PATH

# confirm installation
RUN node -v
RUN npm -v

RUN curl -o- -L https://yarnpkg.com/install.sh | bash

RUN docker-php-ext-install -j$(nproc) pdo_mysql
RUN a2enmod headers
RUN ["/init-container.sh"]

WORKDIR /var/www/html

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER 1
# contains dev-mode packages
RUN composer global require "hirak/prestissimo:^0.3" "sllh/composer-versions-check:^2.0" "pyrech/composer-changelogs:^1.5" --prefer-dist --no-progress --no-suggest --classmap-authoritative

EXPOSE 80
