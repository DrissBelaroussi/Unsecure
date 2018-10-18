FROM php:7.2-apache

# Install ssmtp for sendmail
# See https://github.com/docker-library/php/issues/135
RUN apt-get update && apt-get install -q -y ssmtp mailutils libpng-dev libfreetype6-dev


RUN docker-php-ext-configure gd \
        --with-freetype-dir=/usr/include/freetype2 \
    && docker-php-ext-install gd

# Workaround for write permission on write to MacOS X volumes
# See https://github.com/boot2docker/boot2docker/pull/534
RUN usermod -u 1000 www-data

# Enable Apache mod_rewrite
RUN a2enmod rewrite

RUN service apache2 restart
