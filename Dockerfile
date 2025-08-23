# Use an official PHP image with FPM (FastCGI Process Manager)
FROM php:8.1-fpm

# Install necessary PHP extensions and tools
# mysqli is required for your database connection
# git and unzip are often needed for composer
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install mysqli pdo_mysql zip \
    && docker-php-ext-enable mysqli pdo_mysql zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set the working directory in the container
WORKDIR /var/www/html

# Copy the application code
COPY . /var/www/html

# Install Composer dependencies
RUN composer install --no-dev --optimize-autoloader

# Recommended production PHP configurations (optional, but good practice)
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
RUN echo "opcache.enable=1" >> "$PHP_INI_DIR/php.ini"
RUN echo "opcache.revalidate_freq=0" >> "$PHP_INI_DIR/php.ini"
RUN echo "opcache.validate_timestamps=0" >> "$PHP_INI_DIR/php.ini"
RUN echo "opcache.max_accelerated_files=10000" >> "$PHP_INI_DIR/php.ini"
RUN echo "opcache.memory_consumption=128" >> "$PHP_INI_DIR/php.ini"
RUN echo "opcache.interned_strings_buffer=8" >> "$PHP_INI_DIR/php.ini"
RUN echo "display_errors=Off" >> "$PHP_INI_DIR/php.ini"
RUN echo "log_errors=On" >> "$PHP_INI_DIR/php.ini"
RUN echo "error_log=/var/log/php_errors.log" >> "$PHP_INI_DIR/php.ini"

# Create a log file for PHP errors
RUN touch /var/log/php_errors.log && chmod 666 /var/log/php_errors.log

# Expose port 9000 for FPM
EXPOSE 9000

CMD ["php-fpm"]
