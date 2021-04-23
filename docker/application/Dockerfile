FROM php:7.4.0-fpm-buster

# Use the default production configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# PHP extensions
RUN apt-get update && apt-get install -y libpq-dev libpng-dev git zip unzip libzip-dev \
    && docker-php-ext-configure zip  \
    && docker-php-ext-install pdo pdo_mysql bcmath pdo_pgsql gd zip

RUN apt install -y libxml2-dev
RUN docker-php-ext-install xml
RUN docker-php-ext-install xmlrpc
RUN apt-get install -y graphviz


# Composer
RUN apt-get install -y curl
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer

# Supervisor
RUN apt-get install -y supervisor
#ADD docker/application/config/supervisor/supervisord.conf /etc/supervisord.conf
ENTRYPOINT ["/usr/bin/supervisord", "-n", "-c",  "/var/www/docker/application/config/supervisord.conf"]


RUN docker-php-ext-install opcache


WORKDIR /var/www

USER ${USER_NAME}
