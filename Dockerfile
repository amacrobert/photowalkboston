FROM bref/php-82-fpm:1.7.24

RUN yum install -y git gzip zip unzip tar
COPY --from=composer:2.5 /usr/bin/composer /usr/local/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN git config --global --add safe.directory '*'

# From here on should be separated into a development image
RUN echo 'alias ll="ls -lAh"' >> /root/.bashrc

COPY --from=bref/extra-pcov-php-82:0 /opt /opt
RUN curl -sS https://get.symfony.com/cli/installer | bash
ENV PATH /root/.symfony5/bin:$PATH
RUN symfony server:ca:install

WORKDIR /var/task
