#!/bin/bash

echo "========================================"
echo "  Optik Rapi - Update Script"
echo "========================================"

# 1. Pull perubahan terbaru
echo ""
echo "[1/5] Pulling latest changes from GitHub..."
git pull origin main

# 2. Update dependencies
echo ""
echo "[2/5] Updating composer dependencies..."
composer install --no-dev --optimize-autoloader

# 3. Fix permission storage (jaga-jaga)
echo ""
echo "[3/5] Fixing storage permissions..."
mkdir -p storage/logs
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# 4. Jalankan migration baru (jika ada)
echo ""
echo "[4/5] Running new migrations..."
php artisan migrate --force

# 5. Clear cache agar perubahan aktif
echo ""
echo "[5/5] Clearing and optimizing cache..."
php artisan optimize:clear
php artisan optimize

echo ""
echo "========================================"
echo "  Update selesai!"
echo "========================================"
