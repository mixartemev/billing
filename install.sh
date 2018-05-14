#!/bin/bash

#== Bash helpers ==
function info {
  echo " "
  echo "--> $1"
  echo " "
}

#1. get dependencies
info "Get dependencies"
composer install
echo "Done!"

#2. set db access
info "Configure database access"
sed -i "s/{db_login}/$1/" config/db.php
sed -i "s/{db_password}/$2/" config/db.php
echo "Done!"

#3. create db
info "Create database"
mysqladmin -u $1 -fp$2 create billing
echo "Done!"

#4. fill db
info "Create database structure and fill it"
php yii migrate --interactive=0
echo "Done!"
