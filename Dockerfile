FROM php:8.2-apache

# Instala as extensões necessárias para o PHP conectar no MySQL
RUN docker-php-ext-install pdo pdo_mysql