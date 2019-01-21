FROM php:7.1.8-apache
LABEL maintainer="Richard Soares" 
COPY . /srv/app
COPY .docker/vhost.conf /etc/apache2/sites-available/000-default.conf
COPY .docker/custom.ini /usr/local/etc/php/conf.d/custom.ini
WORKDIR /srv/app
RUN apt-get update

# Permissions
RUN chown -R www-data:www-data /srv/app \
    && useradd -m docker && echo "docker:docker" | chpasswd \ 
    && adduser docker sudo \
    && apt-get -y install sudo nano

# Libraries
RUN docker-php-ext-install mbstring pdo pdo_mysql
# GD Lib and dependancies
RUN apt-get install -y \
    libfreetype6-dev \
    libwebp-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libgmp-dev \
    libldap2-dev \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-webp-dir=/usr/include/  --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install gd 

# Enable rewrites and restart Apache
RUN sudo a2enmod rewrite \
    && service apache2 restart
