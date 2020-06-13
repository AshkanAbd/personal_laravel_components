#!/usr/bin/env bash
cd "$(dirname -- "$(realpath -- "$0")")" || exit
echo 'Linking scripts to home...'
SCRIPT_PATH=${PWD}
cd ../../../../ || exit
NAME=${PWD##*/}
cd ~/ || exit
ln -sr "${SCRIPT_PATH}" ./"$NAME"
echo 'Linking successfull'
exit
