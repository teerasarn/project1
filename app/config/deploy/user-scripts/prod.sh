#!/bin/bash
set -x
exec > >(tee /var/log/user-data.log|logger -t user-data -s 2>/dev/console) 2>&1

sudo a2dissite staging.ultralift.com
sudo service apache2 restart

cd /var/www/ultralift/www.ultralift-transformer.ca/deploy

sudo -u ultralift git checkout .
sudo -u ultralift git pull

sudo rm app/config/parameters.yml
sudo cp app/config/parameters_prod.yml app/config/parameters.yml

sudo rm -rf app/cache app/logs
sudo mkdir -p app/cache/prod app/logs
sudo chown -R ultralift:www-data app/cache app/logs app/config/parameters.yml
sudo chmod -R g+w app/cache app/logs
sudo chmod +t app/cache app/logs

exit 0