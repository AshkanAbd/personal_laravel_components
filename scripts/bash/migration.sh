#!/usr/bin/env bash
cd "$(dirname -- "$(realpath -- "$0")")" || exit
cd ../../../../ || exit
echo 'Enter maintenance mode'
php artisan down
echo 'Composer install'
composer install >>/dev/null 2>&1
echo 'Migrate fresh...'
echo "yes" | php artisan migrate:fresh >>/dev/null 2>&1
echo 'Seeding...'
echo "yes" | php artisan db:seed >>/dev/null 2>&1
echo 'Install passport...'
php artisan passport:install >>/dev/null 2>&1
echo 'Exit maintenance mode'
php artisan up
echo 'Operation was successfull'
exit
