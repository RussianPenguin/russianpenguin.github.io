---
layout: post
title: 'PHP: указываем include_path в качестве аргумента'
type: post
categories:
- Разработка
- HowTo
tags:
- linux
- php
permalink: "/2014/05/16/php-%d1%83%d0%ba%d0%b0%d0%b7%d1%8b%d0%b2%d0%b0%d0%b5%d0%bc-include_path-%d0%b2-%d0%ba%d0%b0%d1%87%d0%b5%d1%81%d1%82%d0%b2%d0%b5-%d0%b0%d1%80%d0%b3%d1%83%d0%bc%d0%b5%d0%bd%d1%82%d0%b0/"
---
Не люблю, когда в глобальном конфиге на рабочей машине появляются include_path, которых там быть не должно (например они ведут в локальную папку пользователя).

Делаем алиас и не заморачиваемся :)

```shell
$ alias "php=/usr/bin/php -d ""include_path='.:/usr/share/pear:/another/include/path'"""
$ php -r "print ini_get('include_path');" .:/usr/share/pear:/another/include/path
```

И можно заставлять работать таким образом разные комманд-лайн утилиты, которым позарез нужен include_path с нашими локальными либами.

