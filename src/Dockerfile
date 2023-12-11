# Use the official Nginx image
FROM nginx:1.21.1

# Set the working directory
WORKDIR /var/www/html

# Copy Laravel files to the container
COPY . .

# Copy Nginx configuration
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Install Composer
RUN apt-get update \
    && apt-get install -y curl \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP extensions and Laravel dependencies
RUN apt-get install -y php8.2-fpm php8.2-cli php8.2-gd php8.2-mysql php8.2-curl php8.2-intl \
    && composer install --optimize-autoloader --no-dev

# Set the correct permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 80
EXPOSE 80

# Start Nginx and PHP-FPM
CMD ["nginx", "-g", "daemon off;"]
