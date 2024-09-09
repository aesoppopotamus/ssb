# Use an official PHP-FPM image
FROM php:7.4-fpm

# Set working directory
WORKDIR /var/www/html

# Install PHP extensions and system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    && docker-php-ext-install mysqli pdo pdo_mysql

    # Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY ./php.ini /usr/local/etc/php/

# Copy all project files to the container
COPY ./src /var/www/html

# Install PHPUnit and other dependencies
RUN composer require --dev phpunit/phpunit:^9.5

# Expose port 9000 for PHP-FPM
EXPOSE 9000
CMD ["php-fpm"]