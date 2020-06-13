@echo off
echo Sending commands to server...
echo sh ./project_root/migration.sh ; exit | plink root@127.0.0.1 -P 22 -pw MyPassword
echo Operation was successful.
