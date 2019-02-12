#!/usr/bin/env bash
composer install
drush cr -y
drush updb -y
drush cim -y
drush entup -y