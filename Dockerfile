FROM bref/php-82-fpm

RUN yum install -y git gzip zip unzip python3 tar sudo passwd
COPY --from=composer:2.4 /usr/bin/composer /usr/local/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

#RUN sudo useradd docker && passwd -l docker
#RUN echo '%docker ALL=(ALL) NOPASSWD:ALL' >> /etc/sudoers
#USER docker

# From here on should be separated into a development image
RUN echo 'alias ll="ls -lAh"' >> /root/.bashrc

COPY --from=bref/extra-pcov-php-82 /opt /opt
RUN curl -sS https://get.symfony.com/cli/installer | bash
ENV PATH /root/.symfony5/bin:$PATH
RUN symfony server:ca:install

WORKDIR /var/task
