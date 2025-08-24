# Stage 1: Build Stage (install dependencies)
# Use an official PHP image with FPM and Composer for the build process
FROM php:8.1-fpm as builder

# Install system dependencies required for PHP extensions and Composer
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install mysqli pdo_mysql zip

# Install Composer globally
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set the working directory
WORKDIR /var/www/html

# Copy only composer files first to leverage Docker cache
COPY composer.json composer.lock ./

# Install Composer dependencies
# --no-scripts prevents execution of scripts that might not be needed in the final image
RUN composer install --no-dev --no-interaction --no-progress --optimize-autoloader --no-scripts

# Copy the rest of the application code
COPY . .

# Stage 2: Production Stage
# Use a clean PHP FPM image for the final production environment
FROM php:8.1-fpm

# Set the working directory
WORKDIR /var/www/html

# Install only the necessary PHP extensions for running the application
RUN apt-get update && apt-get install -y libzip-dev \
    && docker-php-ext-install mysqli pdo_mysql zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Copy the application code and vendor directory from the builder stage
# Set ownership to www-data to avoid permission issues
COPY --from=builder /var/www/html /var/www/html
COPY --chown=www-data:www-data . /var/www/html

# Use the production php.ini configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Custom PHP configurations for production performance and security
# Appending all settings in a single RUN command
RUN { \
    echo "opcache.enable=1"; \
    echo "opcache.memory_consumption=128"; \
    echo "opcache.interned_strings_buffer=8"; \
    echo "opcache.max_accelerated_files=10000"; \
    echo "opcache.revalidate_freq=0"; \
    echo "opcache.validate_timestamps=0"; \
    echo "display_errors=Off"; \
    echo "log_errors=On"; \
    echo "error_log=/proc/self/fd/2"; \
} > $PHP_INI_DIR/conf.d/99-app-optimizations.ini

# Expose port 9000 for FPM
EXPOSE 9000

# Start the PHP-FPM process
CMD ["php-fpm"]
