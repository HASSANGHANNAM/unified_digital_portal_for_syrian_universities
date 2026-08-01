#!/bin/bash

# الانتظار لبضع ثوانٍ للتأكد من أن قاعدة البيانات جاهزة (اختياري)
sleep 5

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

# بدء تشغيل Apache (الأمر الرسمي لصورة php:apache)
exec apache2-foreground
