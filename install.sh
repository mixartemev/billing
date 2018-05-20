#!/usr/bin/env bash

read -e -p "db login: " -i "root" db_login
read -p "db password: " db_password
read -e -p "full path to your project dir: " -i "~/www/billing" project_dir

echo "#1. --> Get dependencies"
composer install
echo "Done!"

echo "#2. --> Configure database access"
sed -i "s/{db_login}/$db_login/" config/db.php
sed -i "s/{db_password}/$db_password/" config/db.php
echo "Done!"

echo "#3. --> Create database"
mysqladmin -u $db_login -fp$db_password create billing
echo "Done!"

echo "#4. --> Create database structure and fill it"
php yii migrate --interactive=0
echo "Done!"

echo "#5. --> Set up daily currency rate logger"
sudo sh -c "echo '0 11 * * * $project_dir/yii cli/get-currency-rates' >> /var/spool/cron/crontabs/`whoami`"
echo "Done!"
