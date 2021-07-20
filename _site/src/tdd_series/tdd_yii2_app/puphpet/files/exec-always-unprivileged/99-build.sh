#!/usr/bin/env bash
echo "Deploy application"
COMPOSER=composer
COMPOSER_OPT=""

if [ "$YII_ENV" = "prod" ]; then
  COMPOSER_OPT="${COMPOSER_OPT} --no-dev"
fi
if [ "$YII_DEbUG" = 1 ] || [ "$YII_DEBUG" = true ]; then
  COMPOSER_OPT="${COMPOSER_OPT} -a"
fi

su - $USER -c "cd /var/www && ${COMPOSER} install ${COMPOSER_OPT}"
