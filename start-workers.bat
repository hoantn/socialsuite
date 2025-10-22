@echo off
cd /d D:\xampp\htdocs\socialsuite
start "" cmd /k "php artisan schedule:work"
start "" cmd /k "php artisan queue:work --sleep=1 --tries=3 --timeout=120"
