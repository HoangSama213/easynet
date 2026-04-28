FROM php:8.2-apache

RUN docker-php-ext-install pdo_mysql

# copy code
COPY . /var/www/html/

# bật .htaccess
RUN a2enmod rewrite
