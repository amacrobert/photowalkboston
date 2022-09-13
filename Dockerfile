FROM php:7.4-fpm

RUN apt-get update -y \
    && apt-get install -y \
        vim git gzip zip unzip python3 curl

RUN docker-php-ext-install pdo_mysql

COPY --from=composer:2.4 /usr/bin/composer /usr/local/bin/composer

RUN pecl install pcov && docker-php-ext-enable pcov

# From here on should be separated into a development image

RUN echo 'alias ll="ls -lAh"' >> /root/.bashrc

COPY --from=bref/extra-pcov-php-74 /opt /opt

# Symfony server
RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash \
    && apt install -y symfony-cli

RUN symfony server:ca:install

WORKDIR /var/task
