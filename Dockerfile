FROM php:8.4-fpm

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN apt update && apt install -y \
    libpq-dev \
    libonig-dev \
    libzip-dev \
    git \
    unzip \
    && docker-php-ext-install \
    pgsql \
    pdo \
    pdo_pgsql \
    mbstring \
    zip

RUN pecl install -o -f xdebug \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable xdebug

RUN echo "zend_extension=xdebug.so" > /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.discover_client_host=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.var_display_max_children=128" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.idekey=docker" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

COPY composer.* ./
RUN composer install --no-scripts --no-autoloader
