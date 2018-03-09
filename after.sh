#!/bin/sh

# If you would like to do some extra provisioning you may
# add any commands you wish to this file and they will
# be run after the Homestead machine is provisioned.

cd ~/site

# Run Composer
composer install

# Migrate & Seed data
php artisan migrate:refresh --seed

# NPM Packages
npm install

cd ~/server

npm install
