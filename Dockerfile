FROM php:8.2-apache

# Install PDO MySQL extension for database connectivity
RUN docker-php-ext-install pdo pdo_mysql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy all project files to the web server directory
COPY . /var/www/html/

# Create the uploads folders and set permissions for photo uploads
RUN mkdir -p /var/www/html/uploads/reviews /var/www/html/uploads/travels \
    && chown -R www-data:www-data /var/www/html/uploads \
    && chmod -R 777 /var/www/html/uploads

# Set working directory
WORKDIR /var/www/html/

# Expose port 80
EXPOSE 80
