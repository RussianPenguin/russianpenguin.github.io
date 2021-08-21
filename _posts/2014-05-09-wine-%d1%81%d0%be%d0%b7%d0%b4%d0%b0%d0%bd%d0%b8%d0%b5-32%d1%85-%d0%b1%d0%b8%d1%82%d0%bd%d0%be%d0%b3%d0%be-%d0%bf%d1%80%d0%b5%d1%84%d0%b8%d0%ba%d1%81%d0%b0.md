---
layout: post
title: 'WINE: создание 32х битного префикса'
date: 2014-05-09 16:41:28.000000000 +04:00
type: post
categories:
- HowTo
tags:
- linux
- wine
permalink: "/2014/05/09/wine-%d1%81%d0%be%d0%b7%d0%b4%d0%b0%d0%bd%d0%b8%d0%b5-32%d1%85-%d0%b1%d0%b8%d1%82%d0%bd%d0%be%d0%b3%d0%be-%d0%bf%d1%80%d0%b5%d1%84%d0%b8%d0%ba%d1%81%d0%b0/"
---
Некоторым приложениям, которые запускаются в wine нужно создание 32х битного префикса вместо 64х битного (это если у вас 64х битное окружение).

```
WINEPREFIX='/home/username/prefix32' WINEARCH='win32' wine 'wineboot'
```

Директории /home/username/prefix32 не должно существовать. Иначе wine ляжет с ошибкой.

