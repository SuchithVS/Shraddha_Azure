FROM php:7.4-fpm

# Install PDO MySQL driver
RUN docker-php-ext-install pdo pdo_mysql

# Install mysqli extension
RUN docker-php-ext-install mysqli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    default-mysql-client \
    && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /var/www/html

# Copy existing application directory contents
COPY . /var/www/html

# Change ownership of our applications
RUN chown -R www-data:www-data /var/www/html