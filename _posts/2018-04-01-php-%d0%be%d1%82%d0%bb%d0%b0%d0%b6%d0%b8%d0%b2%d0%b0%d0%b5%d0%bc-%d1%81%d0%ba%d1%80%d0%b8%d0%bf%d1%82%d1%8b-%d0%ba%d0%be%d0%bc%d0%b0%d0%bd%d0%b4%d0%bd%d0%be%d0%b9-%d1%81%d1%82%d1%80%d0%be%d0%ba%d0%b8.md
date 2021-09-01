---
layout: post
title: 'PHP: Отлаживаем скрипты командной строки на удаленной машине'
date: 2018-04-01 16:17:32.000000000 +03:00
type: post
categories:
- Разработка
- HowTo
tags:
- php
- phpstorm
- xdebug
permalink: "/2018/04/01/php-%d0%be%d1%82%d0%bb%d0%b0%d0%b6%d0%b8%d0%b2%d0%b0%d0%b5%d0%bc-%d1%81%d0%ba%d1%80%d0%b8%d0%bf%d1%82%d1%8b-%d0%ba%d0%be%d0%bc%d0%b0%d0%bd%d0%b4%d0%bd%d0%be%d0%b9-%d1%81%d1%82%d1%80%d0%be%d0%ba%d0%b8/"
excerpt: Отлаживать бэкенд-составляющую проектов не составляет труда, но что делать
  когда требуется отлаживать консольные скрипты?
---
![01-Превью]({{ site.baseurl }}/assets/images/2018/04/01-d0bfd180d0b5d0b2d18cd18e.png)Отладка бекенда на PHP уже ни у кого не вызывает проблем: достаточно правильно настроить расширение [xdebug](https://xdebug.org/) (или [zend debugger](https://www.jetbrains.com/help/phpstorm/configuring-zend-debugger.html)), поставить  [расширение](https://chrome.google.com/webstore/detail/xdebug-helper/eadndfjplgieldjbigjakmdgkmoaaaoc?hl=ru) в свой браузер и можно отлаживать, трассировать или профилировать бекенд.

Но что делать, когда нам требуется отладить консольную утилиту на удаленном сервере? В браузере выбрать пункте enable xdebug нельзя, а если у нас и получится передать IDE_KEY, то оно не знает, где располагается среда разработки и куда делать connect_back.

Это все легко решается одним маленьким скриптом (Важно сделать замечание: это будет работать только когда мы подключены по SSH).

```shell
#!/usr/bin/env bash  
IP=`echo $SSH_CLIENT | awk "{print $1}"​`  
PHP='/usr/bin/php -d 'xdebug.remote_host=${IP}' -d 'xdebug.remote_autostart=1''  
$PHP $@
```

Теперь достаточно скомандовать

```shell
$ php-debug.sh yii
```

И на рабочей машине мы сразу увидим запрос на подключение.

![02-Запрос на настройку путей]({{ site.baseurl }}/assets/images/2018/04/02-d0b7d0b0d0bfd180d0bed181-d0bdd0b0-d0bdd0b0d181d182d180d0bed0b9d0bad183-d0bfd183d182d0b5d0b9.png)

