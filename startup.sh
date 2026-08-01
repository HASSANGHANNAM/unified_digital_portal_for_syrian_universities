#!/bin/bash

# 🛠️ إصلاح صلاحيات مجلدات Laruhan الأساسية
echo "Fixing storage and cache permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

# التأكد من وجود ملف laravel.log وإنشائه بصلاحيات صحيحة
touch /var/www/html/storage/logs/laravel.log
chown www-data:www-data /var/www/html/storage/logs/laravel.log
chmod 644 /var/www/html/storage/logs/laravel.log

# الانتظار لبضع ثوانٍ (اختياري)
sleep 3

# تشغيل أوامر Laravel الأساسية
echo "Running package:discover..."
php artisan package:discover --ansi

echo "Running vendor:publish..."
php artisan vendor:publish --tag=laravel-assets --ansi --force

echo "Clearing and caching config..."
php artisan config:clear
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Caching views..."
php artisan view:cache

echo "Running migrations..."
php artisan migrate --force

echo "Optimizing..."
php artisan optimize

# بدء تشغيل Apache
exec apache2-foreground