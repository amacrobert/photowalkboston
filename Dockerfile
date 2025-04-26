FROM bref/php-83-fpm:2

RUN yum install -y git gzip zip unzip tar
COPY --from=composer:2.8 /usr/bin/composer /usr/local/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN git config --global --add safe.directory '*'

# From here on should be separated into a development image
RUN echo 'alias ll="ls -lAh"' >> /root/.bashrc

COPY --from=bref/extra-pcov-php-82:1 /opt /opt
RUN curl -sS https://get.symfony.com/cli/installer | bash
ENV PATH /root/.symfony5/bin:$PATH
RUN symfony server:ca:install

WORKDIR /var/task
