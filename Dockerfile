# syntax=docker/dockerfile:1

FROM php:8.2-apache
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

# /home/aye dir for use with NPM install
RUN mkdir /home/aye

RUN useradd aye
RUN chmod -R 775 /home/aye
RUN chown -R aye:aye /home/aye
RUN chmod -R 775 /var/www/html
RUN chown -R aye:aye /var/www/html
USER aye

RUN composer install

### From NPM https://github.com/nvm-sh/nvm
# Use bash for the shell
SHELL ["/bin/bash", "-o", "pipefail", "-c"]

# Create a script file sourced by both interactive and non-interactive bash shells
ENV BASH_ENV /home/aye/.bash_env
RUN touch "${BASH_ENV}"
RUN echo '. "${BASH_ENV}"' >> ~/.bashrc

# Download and install nvm
RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.2/install.sh | PROFILE="${BASH_ENV}" bash
RUN echo node > .nvmrc
RUN nvm install 18.12.1
