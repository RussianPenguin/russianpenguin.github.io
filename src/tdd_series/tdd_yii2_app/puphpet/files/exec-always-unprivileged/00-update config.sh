#!/usr/bin/env bash
echo "Deploy config files"
su - $USER -c "cd /var/www && php init --env=${YII_ENV} --overwrite=y"

