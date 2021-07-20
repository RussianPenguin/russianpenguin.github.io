#!/usr/bin/env sh

IP=`echo $SSH_CLIENT | awk '{print $1}'`
echo #IP
PHP='/usr/bin/env php -d 'xdebug.remote_host=${IP}' -d 'xdebug.remote_autostart=1''
$PHP "$@"