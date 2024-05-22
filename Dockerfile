FROM php:8.2-fpm

#custom php.ini
#COPY .docker/php/php.ini /usr/local/etc/php/php.ini
RUN apt-get update
RUN apt-get install -y \
    libpq-dev \
    libzip-dev \
    libicu-dev

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl pdo_mysql pgsql pdo_pgsql mysqli zip

WORKDIR /var/www/html/

# 5. Composer
RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer
RUN chmod +x /usr/local/bin/composer
RUN composer self-update

ENV TZ="Asia/Bangkok"
COPY ./src/ .
ENV COMPOSER_ALLOW_SUPERUSER=1

RUN composer install

CMD ["php", "-S", "0.0.0.0:80", "-t", "public"]
