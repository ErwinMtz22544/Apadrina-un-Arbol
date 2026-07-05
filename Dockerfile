FROM php:8.2-apache
# Instalar y habilitar el driver de MySQL (mysqli)
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
COPY . /var/www/html/
EXPOSE 80