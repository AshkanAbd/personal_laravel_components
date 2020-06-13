#!/usr/bin/env bash
cd "$(dirname -- "$(realpath -- "$0")")" || exit
cd ../../../../ || exit
echo 'Enter maintenance mode'
php artisan down
echo 'Pull...'
if ! git pull >>/dev/null 2>&1; then
  echo "Can't pull, try git stash"
  git stash >>/dev/null 2>&1
  echo "Pull agian..."
  if ! git pull >>/dev/null 2>&1; then
    echo "Can't pull, even after git stash."
    echo "Check git repo."
    exit
  fi
fi
echo 'Success'
echo 'Composer install...'
composer install >>/dev/null 2>&1
echo 'Fix storage link...'
rm -rf public/storage
php artisan storage:link >>/dev/null 2>&1
echo 'Fix permissions'
chown www-data:www-data -R storage public >>/dev/null 2>&1
echo 'Exit maintenance mode'
php artisan up
echo 'Operation was successfull'
exit
