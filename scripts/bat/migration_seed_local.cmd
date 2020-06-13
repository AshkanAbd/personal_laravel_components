@echo off
cd ../../../../
echo yes | php artisan migrate:fresh
echo yes | php artisan db:seed
php artisan passport:install
