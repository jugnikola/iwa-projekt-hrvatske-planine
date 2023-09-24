# Use the official PHP 7.4 Apache base image
FROM php:7.4-apache

# Copy virtual host file into the container
COPY ./docker/000-default.conf /etc/apache2/sites-available/000-default.conf

# Enable rewrite mode
RUN a2enmod rewrite

# Update package lists and install necessary packages
RUN apt-get update && \
    apt-get install -y \
    libzip-dev \
    git \
    wget \
    unzip \
    --no-install-recommends \
    && rm -r /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install zip pdo_mysql mysqli

# Copy php.ini configuration
COPY ./docker/php.ini /usr/local/etc/php/php.ini

# Change the current working directory
WORKDIR /var/www

# Change the owner of the container document root
RUN chown -R www-data:www-data /var/www

# Start Apache in the foreground
CMD ["apache2-foreground"]
