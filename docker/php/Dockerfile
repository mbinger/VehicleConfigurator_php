FROM php:8.3-fpm

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
  && docker-php-ext-install zip mysqli pdo_mysql \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j$(nproc) gd \
  && docker-php-ext-install exif

# Copy Composer from the official Composer image
COPY --from=composer:2.8.5 /usr/bin/composer /usr/bin/composer

# Add custom PHP configuration
COPY php.ini /usr/local/etc/php/conf.d/40-custom.ini

WORKDIR /var/www/html
