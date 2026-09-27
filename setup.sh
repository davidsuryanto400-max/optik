#!/bin/bash

echo "========================================"
echo "  Optik Rapi - Setup Script"
echo "========================================"

# 1. Install dependencies
echo ""
echo "[1/7] Installing composer dependencies..."
composer install --no-dev --optimize-autoloader

# 2. Buat .env jika belum ada
echo ""
echo "[2/7] Setting up .env file..."
if [ ! -f .env ]; then
    cp .env.example .env
    echo "  .env file created from .env.example"
    echo "  >>> EDIT .env sesuaikan DB, APP_URL, dll <<<"
else
    echo "  .env already exists, skipping..."
fi

# 3. Generate app key
echo ""
echo "[3/7] Generating application key..."
php artisan key:generate

# 4. Buat folder storage & set permission
echo ""
echo "[4/7] Setting storage permissions..."
mkdir -p storage/logs
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/app/public
chmod -R 775 storage
chmod -R 775 bootstrap/cache
echo "  Storage permissions set to 775"

# 5. Jalankan migration + seeder
echo ""
echo "[5/7] Running migrations and seeders..."
php artisan migrate --seed --force

# 6. Storage link
echo ""
echo "[6/7] Creating storage symlink..."
php artisan storage:link

# 7. Clear & cache config
echo ""
echo "[7/7] Optimizing application..."
php artisan optimize:clear
php artisan optimize

echo ""
echo "========================================"
echo "  Setup selesai!"
echo "  Login dengan: admin / 12345678"
echo "========================================"
