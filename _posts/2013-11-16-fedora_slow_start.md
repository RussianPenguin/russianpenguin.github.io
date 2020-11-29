---
layout: post
title: Очень медленная загрузка Fedora
date: 2013-11-16 13:40:15.000000000 +04:00
type: post
parent_id: '0'
published: true
password: ''
status: publish
categories:
- HowTo
tags:
- linux
meta:
  _publicize_pending: '1'
  _edit_last: '13696577'
  original_post_id: '31'
  _wp_old_slug: '31'
  _wpcom_is_markdown: '1'
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
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
