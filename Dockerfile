FROM bref/php-81-fpm

RUN yum install -y git gzip zip unzip python3

COPY --from=composer:2.2 /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/task

# From here on should be separated into a development image

RUN echo 'alias ll="ls -lAh"' >> /root/.bashrc

COPY --from=bref/extra-pcov-php-81 /opt /opt

# Symfony server
RUN curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony/bin/symfony /usr/local/bin/symfony \
    && symfony server:ca:install
