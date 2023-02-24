FROM php:8.1-apache

# PHP Extensions
RUN docker-php-ext-install -j$(nproc) mysqli pdo pdo_mysql session
RUN pecl config-set php_ini "${PHP_INI_DIR}/php.ini" && \
    pecl install redis
RUN docker-php-ext-enable redis

# Mods
RUN a2enmod rewrite proxy

# Apache Confs Setup
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
COPY _docker-assets/apache/sites-available /etc/apache2/sites-available/
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Composer Installation
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php && \
    php -r "unlink('composer-setup.php');" && \
    chmod +x composer.phar && \
    mv composer.phar /usr/bin/composer

# Copying the Source
COPY . /var/www/html/

# Running Composer
RUN /usr/bin/composer install
