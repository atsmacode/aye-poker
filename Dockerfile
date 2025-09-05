# syntax=docker/dockerfile:1

FROM php:8.2-apache
COPY . /var/www/html
COPY docker/apache.conf /etc/apache2/sites-enabled/000-default.conf 
RUN a2enmod rewrite

WORKDIR /var/www/html

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apt-get update && apt-get install -y \
        zip \
		libfreetype-dev \
		libjpeg62-turbo-dev \
		libpng-dev \
	&& docker-php-ext-configure gd --with-freetype --with-jpeg \
	&& docker-php-ext-install pdo pdo_mysql mysqli

# Install Node 18.15.0
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
	&& apt install -y nodejs \
    && npm install -g n \
    && n 23.10.0 \
    && apt remove -y nodejs \
    && apt autoremove -y

RUN composer install

RUN mkdir /var/www/html/public/var

# Permissions for Symfony app cache, logs etc
RUN chown -R www-data:www-data /var/www/html/public/var \
   && chmod -R ug+rwx /var/www/html/public/var

RUN node -v && npm -v
RUN npm install
