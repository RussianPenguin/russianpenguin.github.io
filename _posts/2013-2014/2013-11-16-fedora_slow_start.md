---
layout: post
title: Очень медленная загрузка Fedora
type: post
categories:
- HowTo
tags:
- linux
permalink: "/2013/11/16/fedora_slow_start/"
---
Неделю пытался понять, что не так с системой. Почему она медленно грузится.

Все просто: используется btrfs, нет ограничения на размер журнала, для каталога журналов не отключена copy-on-write.

```
# chattr +C /var/log/journal
```

Так же стоит лимитировать объем файлов журнала

/etc/systemd/journald.conf

```
SystemMaxUse=50M
```
