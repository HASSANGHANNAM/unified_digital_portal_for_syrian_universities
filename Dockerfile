FROM php:8.3-apache

# تثبيت المتطلبات
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# إضافة مهمة جداً: تفعيل إعادة كتابة الروابط (mod_rewrite) في Apache
RUN a2enmod rewrite

# تثبيت Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# تعيين حد الذاكرة لـ Composer
ENV COMPOSER_MEMORY_LIMIT=-1

# تعيين دليل العمل
WORKDIR /var/www/html

# نسخ جميع الملفات
COPY . .

# إعداد Git safe.directory
RUN git config --global --add safe.directory /var/www/html

# تثبيت الحزم (مع تجاهل الـ scripts مؤقتاً)
RUN composer install --no-dev --optimize-autoloader --ignore-platform-req=ext-* --no-scripts

# نسخ سكريبت بدء التشغيل
COPY startup.sh /usr/local/bin/startup.sh

# جعل سكريبت بدء التشغيل قابلاً للتنفيذ
RUN chmod +x /usr/local/bin/startup.sh

# تعيين الصلاحيات للمجلدات
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# نسخ إعدادات Apache (تأكد من وجود الملف)
COPY vhost.conf /etc/apache2/sites-available/000-default.conf

# تعيين نقطة الدخول لتشغيل السكريبت أولاً، ثم Apache
ENTRYPOINT ["/usr/local/bin/startup.sh"]