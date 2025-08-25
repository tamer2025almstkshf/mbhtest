# Use the official PHP 8.2 image with FPM
FROM php:8.2-fpm

# Install Nginx and other system dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql zip

# Copy the Nginx configuration file
COPY docker/nginx/default.conf /etc/nginx/sites-available/default

# Copy the startup script
COPY start-cloud-run.sh /usr/local/bin/start-cloud-run.sh
RUN chmod +x /usr/local/bin/start-cloud-run.sh

# Set the working directory
WORKDIR /var/www/html

# Copy the rest of the application files
COPY . .

# Expose port 8080
EXPOSE 8080

# Set the entrypoint to the startup script
CMD ["/usr/local/bin/start-cloud-run.sh"]
