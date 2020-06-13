#!/usr/bin/env bash
cd "$(dirname -- "$(realpath -- "$0")")" || exit
cd ../../../../ || exit
echo 'Publishing telescope'
if ! php artisan telescope:publish >>/dev/null 2>&1; then
  echo "Can't publish"
fi
echo 'Success'
exit
