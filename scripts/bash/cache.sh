#!/usr/bin/env bash
cd "$(dirname -- "$(realpath -- "$0")")" || exit
cd ../../../../ || exit
echo 'Enter maintenance mode'
php artisan down
echo 'Clear all caches'
php artisan optimize:clear >>/dev/null 2>&1
echo 'Cache routes...'
php artisan route:cache >>/dev/null 2>&1
echo 'Cache views...'
php artisan view:cache >>/dev/null 2>&1
echo 'Cache configs...'
php artisan config:cache >>/dev/null 2>&1
echo 'Exit maintenance mode'
php artisan up
echo 'Operation was successfull'
exit
