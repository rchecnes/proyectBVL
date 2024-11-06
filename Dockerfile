# Usar la imagen oficial de PHP 8
FROM php:8.0-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nano \
    cron \
    libzip-dev \
    && docker-php-ext-install zip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd mysqli

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar el directorio de trabajo y permisos
WORKDIR /var/www/html/

# Copiar el código del proyecto al contenedor
COPY . .

# Install project dependencies
RUN composer install --no-interaction --optimize-autoloader

# Configurar el puerto que usará el contenedor
CMD ["php-fpm"]