# Build a PHP7, MySQL, Apache server specific to my MVC framework.

# Install base environment
FROM php:7.2-apache

# Enable Apache Rewrite Module
RUN a2enmod rewrite
RUN service apache2 restart

# BASIC Setup with PDO MySQL
RUN apt-get update
RUN docker-php-ext-install pdo_mysql

# ADVANCED Setup with extra PHP extenstions. Ref: https://hub.docker.com/_/php/
#RUN apt-get update
#RUN docker-php-ext-install pdo_mysql
#RUN docker-php-ext-install mbstring
#RUN docker-php-ext-install gd
#RUN docker-php-ext-install zip

# Redefine the public root for apache to work with this MVC web app.
ENV APACHE_DOCUMENT_ROOT /var/www/html/public/
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Copy files from host to container
COPY . /var/www/html/

# Expose ports
EXPOSE 80