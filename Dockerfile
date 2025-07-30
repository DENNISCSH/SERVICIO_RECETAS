# Imagen base oficial de PHP con extensiones necesarias para Laravel
FROM php:8.2-fpm

# Instalar dependencias del sistema y extensiones necesarias
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev libzip-dev unzip git curl libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql pdo_pgsql zip

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Establecer directorio de trabajo
WORKDIR /var/www

# Copiar archivos del proyecto
COPY . .

# Instalar dependencias de Laravel
RUN composer install --optimize-autoloader --no-dev

# Generar clave de aplicación si no existe
RUN php artisan key:generate --force

# Cachear configuración y rutas
RUN php artisan config:cache && php artisan route:cache

# Exponer el puerto que usará Laravel
EXPOSE 8000

# Comando para ejecutar Laravel usando servidor embebido
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
