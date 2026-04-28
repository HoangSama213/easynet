FROM php:8.2-apache

# copy code
COPY . /var/www/html/

# bật .htaccess
RUN a2enmod rewrite