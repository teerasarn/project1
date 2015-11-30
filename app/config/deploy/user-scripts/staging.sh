#!/bin/bash

cd /var/www/ultralift/stage.ultralift-transformer.ca/deploy

sudo rm app/config/parameters.yml

sudo -u ultralift git checkout .
sudo -u ultralift git pull

sudo cp app/config/parameters_prod.yml app/config/parameters.yml

sudo rm -rf app/cache app/logs
sudo mkdir -p app/cache/prod app/logs
sudo chown -R ultralift:www-data app/cache app/logs app/config/parameters.yml
sudo chmod -R g+w app/cache app/logs
sudo chmod +t app/cache app/logs

sudo chown -R ubuntu:ubuntu app/config/deploy/user-scripts
sudo chmod -R 544 app/config/deploy/user-scripts


exit 0