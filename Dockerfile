FROM php:8.2-apache

# Instalar dependencias del sistema necesarias
RUN apt-get update && apt-get install -y \
    libssl-dev \
    git \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Instalar el driver de MongoDB para PHP
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Habilitar mod_rewrite de Apache
RUN a2enmod rewrite

# Copiar los archivos del proyecto al directorio web de Apache
COPY . /var/www/html/

# Exponer el puerto 80
EXPOSE 80