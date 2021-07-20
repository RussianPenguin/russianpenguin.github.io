#!/usr/bin/env bash

# environment
echo "export YII_ENV=${YII_ENV}" > /etc/profile.d/yii_env.sh
echo "export FACTER_YII_ENV=${YII_ENV}" >> /etc/profile.d/yii_env.sh
echo "set YII_ENV=dev${YII_ENV}" > /etc/profile.d/yii_env.csh
echo "set FACTER_YII_ENV=dev${YII_ENV}" >> /etc/profile.d/yii_env.csh
echo "export YII_DEBUG=${YII_DEBUG}" > /etc/profile.d/yii_debug.sh
echo "export FACTER_YII_DEBUG=${YII_DEBUG}" >> /etc/profile.d/yii_debug.sh
echo "set YII_DEBUG=${YII_DEBUG}" > /etc/profile.d/yii_debug.csh
echo "set FACTER_YII_DEBUG=${YII_DEBUG}" >> /etc/profile.d/yii_debug.csh
