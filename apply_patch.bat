@echo off
cd /d %~dp0
powershell -ExecutionPolicy Bypass -File scripts\remove_providers.ps1
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear
echo Da hoan tat patch. Mo http://localhost/socialsuite/public
pause
