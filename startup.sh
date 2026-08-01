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

# 🟢 تشغيل Reverb في الخلفية
echo "🔄 Starting Reverb in background..."
php artisan reverb:start --host=0.0.0.0 --port=8080 &

# 🟢 تشغيل Queue Worker باستخدام Redis في الخلفية
echo "🔄 Starting Queue Worker in background..."
php artisan queue:work redis --queue=default --sleep=3 --tries=3 &

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

# 🟢 طباعة سجل الأخطاء لمعرفة سبب الـ 500
echo "📄 Checking Laravel logs for errors..."
if [ -f /var/www/html/storage/logs/laravel.log ]; then
    echo "========= ERROR LOG DUMP ========="
    cat /var/www/html/storage/logs/laravel.log
    echo "=================================="
else
    echo "No Laravel log file found yet."
fi

echo "✅ Startup script finished. Starting Apache..."

# 4. بدء تشغيل Apache
exec apache2-foreground