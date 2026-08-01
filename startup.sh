#!/bin/bash

# 1. إصلاح الصلاحيات (أساسي لمنع أخطاء Permission Denied)
echo "Fixing permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache
touch /var/www/html/storage/logs/laravel.log
chown www-data:www-data /var/www/html/storage/logs/laravel.log
chmod 644 /var/www/html/storage/logs/laravel.log

# 2. الأوامر الأساسية لربط الحزم (خفيفة وسريعة)
echo "Running package:discover..."
php artisan package:discover --ansi

echo "Running vendor:publish..."
php artisan vendor:publish --tag=laravel-assets --ansi --force

# 3. تخزين الإعدادات مؤقتاً (لتسريع الأداء، وهذا لا يمس قاعدة البيانات)
echo "Caching config, routes, views..."
# php artisan config:cache
# php artisan route:cache
# php artisan view:cache

# ⛔ لا تضع migrate هنا (لأن الداتابيز جاهزة)
# ⛔ لا تضع optimize هنا (لأنها تستهلك وقتاً طويلاً وليست ضرورية للبدء)

# 4. بدء تشغيل Apache
exec apache2-foreground