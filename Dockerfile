FROM php:8.2-cli

# Install system dependencies & Node.js
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libpq-dev \
    nodejs \
    npm \
    && docker-php-ext-install \
    pdo \
    pdo_pgsql \
    pgsql \
    zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy the entire application code first
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install NPM dependencies
RUN npm install

# Build assets for production
RUN npm run build

# Add a debug step to verify asset creation
RUN ls -l public/build

# Optimize configuration for production
RUN php artisan config:cache

# Set permissions
RUN chmod -R 775 storage bootstrap/cache

# Fly.io expects the app to listen on this port
EXPOSE 8080

# Start Laravel
CMD php artisan serve --host=0.0.0.0 --port=8080
