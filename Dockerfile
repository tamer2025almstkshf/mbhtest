# Use an official PHP image with FPM (FastCGI Process Manager)
FROM php:8.1-fpm

# Install necessary PHP extensions
# mysqli is required for your database connection
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Set the working directory in the container
WORKDIR /var/www/html
