FROM php:8.2-apache

# Install PDO MySQL extension for database connectivity
RUN docker-php-ext-install pdo pdo_mysql

# Install SSL CA certificates for TiDB Cloud TLS connections
RUN apt-get update && apt-get install -y ca-certificates && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Increase PHP upload limits for video files (up to 50MB)
RUN echo "upload_max_filesize = 50M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size = 55M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "memory_limit = 128M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "max_execution_time = 120" >> /usr/local/etc/php/conf.d/uploads.ini

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
