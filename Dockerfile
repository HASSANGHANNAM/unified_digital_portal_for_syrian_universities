# استخدم صورة PHP 8.2 مع Apache
FROM php:8.2-apache

# ثبّت ملحقات PHP المطلوبة
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# ثبّت Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# فعّل mod_rewrite في Apache
RUN a2enmod rewrite

# غيّر دليل العمل
WORKDIR /var/www/html

# انسخ جميع ملفات المشروع
COPY . .

# ثبّت تبعيات المشروع
RUN composer install --no-dev --optimize-autoloader --timeout=600
# غيّر صلاحيات الملفات
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# انسخ إعدادات Apache (vhost.conf الموجود في الجذر)
COPY vhost.conf /etc/apache2/sites-available/000-default.conf
