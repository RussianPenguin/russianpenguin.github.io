---
layout: post
title: 'PHP: дизассемблирование'
date: 2014-05-09 18:28:32.000000000 +04:00
type: post
categories:
- HowTo
- JFF
tags:
- php
permalink: "/2014/05/09/php-%d0%b4%d0%b8%d0%b7%d0%b0%d1%81%d1%81%d0%b5%d0%bc%d0%b1%d0%bb%d0%b8%d1%80%d0%be%d0%b2%d0%b0%d0%bd%d0%b8%d0%b5/"
---
Иногда очень интересно посмотреть в недра того, что нагенерировал интерпретатор php. Проще говоря - дизассемблировать :)

Ставим расширение [vld](http://pecl.php.net/package/vld "The Vulcan Logic Disassembler") из pecl.

А потом можем легко смотреть кишки (опкоды) любого скрипта.

```
$ php -d vld.active=1 -d vld.execute=0 -f yourscript.php
```

Можно заглянуть в [статью](http://blog.golemon.com/2008/01/understanding-opcodes.html "Понимаем опкоды"), которая как раз посвящена разбору опкодов. И в официальный [мануал](http://php.net/manual/en/internals2.opcodes.list.php "php opcode list").

