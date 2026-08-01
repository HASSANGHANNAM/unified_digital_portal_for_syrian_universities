#!/bin/bash

echo "🚀 Starting startup script..."

# 1. إصلاح الصلاحيات
echo "Fixing permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache
touch /var/www/html/storage/logs/laravel.log
chown www-data:www-data /var/www/html/storage/logs/laravel.log
chmod 644 /var/www/html/storage/logs/laravel.log

# 2. التحقق من APP_KEY
echo "Checking APP_KEY..."
if [ -z "$APP_KEY" ]; then
  echo "❌ ERROR: APP_KEY is not set!"
else
  echo "✅ APP_KEY is set."
fi

# 3. تنظيف الكاش وتشغيل الأوامر الأساسية (مع تجاهل الأخطاء)
echo "Clearing old cache..."
php artisan optimize:clear 2>&1 || true

echo "Running package:discover..."
php artisan package:discover --ansi 2>&1 || true

echo "Running vendor:publish..."
php artisan vendor:publish --tag=laravel-assets --ansi --force 2>&1 || true

echo "Caching config, routes, views..."
php artisan config:cache 2>&1 || true
php artisan route:cache 2>&1 || true
php artisan view:cache 2>&1 || true

echo "✅ Startup script finished. Starting Apache..."

# 4. بدء تشغيل Apache
exec apache2-foreground