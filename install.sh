#!/bin/bash
#1. clone rep
git clone git@github.com:mixartemev/billing.git
#2. go to project dir
cd billing
#3. get dependencies
composer install
#4. set db access
sed -i "s/{db_login}/$1/" ./config/db.php
sed -i "s/{db_password}/$2/" ./config/db.php
#5. create db
mysqladmin -u $1 -fp$2 create billing
#6. fill db
php yii migrate --interactive=0
