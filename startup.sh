#!/bin/bash

echo "🚀 Starting Laravel application..."

# إصلاح الصلاحيات
echo "Fixing permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache
touch /var/www/html/storage/logs/laravel.log
chown www-data:www-data /var/www/html/storage/logs/laravel.log
chmod 644 /var/www/html/storage/logs/laravel.log

# التحقق من APP_KEY
echo "Checking APP_KEY..."
if [ -z "$APP_KEY" ]; then
  echo "❌ ERROR: APP_KEY is not set! Please add it to Environment Variables."
else
  echo "✅ APP_KEY is set."
fi

# تنظيف الكاش
echo "Clearing old cache..."
php artisan optimize:clear 2>&1 || true

# تشغيل الأوامر الأساسية (تجاهل الأخطاء)
echo "Running package:discover..."
php artisan package:discover --ansi 2>&1 || true

echo "Running vendor:publish..."
php artisan vendor:publish --tag=laravel-assets --ansi --force 2>&1 || true

echo "Caching config, routes, views..."
php artisan config:cache 2>&1 || true
php artisan route:cache 2>&1 || true
php artisan view:cache 2>&1 || true

echo "✅ Startup script finished. Starting Apache..."

# بدء Apache
exec apache2-foreground