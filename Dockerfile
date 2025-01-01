# syntax=docker/dockerfile:1

FROM php:8.1-apache
COPY . /var/www/html
COPY docker/apache.conf /etc/apache2/sites-enabled/000-default.conf 
RUN a2enmod rewrite

WORKDIR /var/www/html

# COPY .env.template .env
COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apt-get update && apt-get install -y \
        zip \
		libfreetype-dev \
		libjpeg62-turbo-dev \
		libpng-dev \
	&& docker-php-ext-configure gd --with-freetype --with-jpeg \
	&& docker-php-ext-install pdo pdo_mysql mysqli

RUN useradd aye
RUN chmod -R 775 /var/www/html
RUN chown -R aye:aye /var/www/html
USER aye

RUN composer install

# RUN php bin/console doctrine:migrations:migrate
