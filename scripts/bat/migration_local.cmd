@echo off
cd ../../../../
echo yes | php artisan migrate:fresh
php artisan passport:install
