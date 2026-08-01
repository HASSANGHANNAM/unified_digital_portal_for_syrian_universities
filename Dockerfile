FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# تفعيل وحدات Apache المطلوبة (بما فيها Proxy للـ WebSocket)
RUN a2enmod rewrite proxy proxy_http proxy_wstunnel

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ENV COMPOSER_MEMORY_LIMIT=-1

WORKDIR /var/www/html

COPY . .

RUN git config --global --add safe.directory /var/www/html

RUN composer install --no-dev --optimize-autoloader --ignore-platform-req=ext-* --no-scripts

COPY startup.sh /usr/local/bin/startup.sh
RUN chmod +x /usr/local/bin/startup.sh

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

COPY vhost.conf /etc/apache2/sites-available/000-default.conf

# كشف المنفذ الذي يستخدمه Reverb
EXPOSE 8080

ENTRYPOINT ["/usr/local/bin/startup.sh"]