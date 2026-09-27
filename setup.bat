@echo off
echo ========================================
echo   Optik Rapi - Setup Script (Windows)
echo ========================================

echo.
echo [1/7] Installing composer dependencies...
composer install

echo.
echo [2/7] Setting up .env file...
if not exist .env (
    copy .env.example .env
    echo   .env file created from .env.example
    echo   ^>^>^> EDIT .env sesuaikan DB, APP_URL, dll ^<^<^<
) else (
    echo   .env already exists, skipping...
)

echo.
echo [3/7] Generating application key...
php artisan key:generate

echo.
echo [4/7] Creating storage folders...
if not exist storage\logs mkdir storage\logs
if not exist storage\framework\cache\data mkdir storage\framework\cache\data
if not exist storage\framework\sessions mkdir storage\framework\sessions
if not exist storage\framework\views mkdir storage\framework\views
if not exist storage\app\public mkdir storage\app\public
echo   Folders created successfully

echo.
echo [5/7] Running migrations and seeders...
php artisan migrate --seed --force

echo.
echo [6/7] Creating storage symlink...
php artisan storage:link

echo.
echo [7/7] Optimizing application...
php artisan optimize:clear
php artisan optimize

echo.
echo ========================================
echo   Setup selesai!
echo   Login dengan: admin / 12345678
echo ========================================
pause
