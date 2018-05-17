#!/usr/bin/env bash

echo "#1. --> Get dependencies"
composer install
echo "Done!"

echo "#2. --> Configure database access"
sed -i "s/{db_login}/$1/" config/db.php
sed -i "s/{db_password}/$2/" config/db.php
echo "Done!"

echo "#3. --> Create database"
mysqladmin -u $1 -fp$2 create billing
echo "Done!"

echo "#4. --> Create database structure and fill it"
php yii migrate --interactive=0
echo "Done!"

echo "#5. --> Set up daily currency rate logger"
sudo sh -c "echo '0 11 * * * ~/billing/yii cli/get-currency-rates' >> /var/spool/cron/crontabs/`whoami`"
echo "Done!"
