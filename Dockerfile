FROM php:8.1-apache

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

COPY _docker-assets/apache/sites-available /etc/apache2/sites-available/

RUN docker-php-ext-install -j$(nproc) mysqli pdo pdo_mysql session
RUN a2enmod rewrite proxy

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

COPY . /var/www/html/
