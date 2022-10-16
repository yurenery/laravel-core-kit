#!/bin/bash

## Go into the project directory.
cd /var/www/project

#Create env
echo "ENVIRONMENT : $ENVIRONMENT";
echo "DB_HOST : $MYSQL_HOST";
echo "DB_USER : $MYSQL_USER";
cp -rf .env.$ENVIRONMENT .env

ln -sf /var/www/docker/php.ini /usr/local/etc/php/conf.d/php.ini
ln -sf /var/www/docker/php-fpm.conf /usr/local/etc/
ln -sf /var/www/docker/www.conf /usr/local/etc/php-fpm.d/www.conf

## Remove old storage link.
## We need to do this, cuz all containers will wait till storage directory is up.
if [ -d "/var/www/project/public/storage" ]
then
    echo "Remove storage link."
    rm /var/www/project/public/storage
fi

## Install composer dependencies and generate ide helpers
export COMPOSER_MEMORY_LIMIT=-1

composer config --global --auth http-basic.repo.packagist.com AttraactGroupUser $PACKAGIST_TOKEN
composer update
#export COMPOSER_MEMORY_LIMIT=-1 && composer generate-ide-helpers

## Do general commands and generate storage directory.
php artisan passport:keys --force
php artisan storage:link
php artisan migrate --seed