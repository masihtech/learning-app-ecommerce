# Use PHP 7.4 with Apache as the base image
FROM php:7.4-apache

# Install the mysqli extension
RUN docker-php-ext-install mysqli

# Copy the application source code to the appropriate directory in the container
COPY . /var/www/html/

# Expose port 80
EXPOSE 80


