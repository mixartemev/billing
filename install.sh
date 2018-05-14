#!/usr/bin/env bash

#1. get dependencies
echo "\n-->Get dependencies\n"
composer install
echo "Done!"

#2. set db access
echo "\n-->Configure database access\n"
sed -i "s/{db_login}/$1/" config/db.php
sed -i "s/{db_password}/$2/" config/db.php
echo "Done!"

#3. create db
echo "\n-->Create database\n"
mysqladmin -u $1 -fp$2 create billing
echo "Done!"

#4. fill db
echo "\n-->Create database structure and fill it\n"
php yii migrate --interactive=0
echo "Done!"
