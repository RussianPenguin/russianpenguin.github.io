---
layout: post
title: 'Raspbian: swapfile'
date: 2015-09-07 13:45:22.000000000 +03:00
type: post
parent_id: '0'
published: true
password: ''
status: publish
categories: []
tags:
- linux
meta:
  _wpcom_is_markdown: '1'
  sharing_disabled: '1'
  _rest_api_published: '1'
  _rest_api_client_id: "-1"
  _publicize_job_id: '14522400442'
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2015/09/07/raspbian-swapfile/"
---
Вот чего точно не думал, так это того, что свопфайл в raspbian (не партиция, а именно файл) подключается не так, как в нормальных дистрибутивах.

Мы же с вами привыкли к тому, что в случае использования свопфайла в /etc/fstab будет запись подобная этой.

[code]/swapfile none swap defaults 0 0[/code]

Так нет. Все не так. Вернее такой формат-то работает, но разработчики дистра очень рекомендуют использовать dphys-swapfile. Даже комментарий оставили (может я чего упустил и в дебиане теперь такое повсеместно?).

А вообще эта штука призвана инициализировать своп только после того, как смонтированы все файловые системы. Эдакая защита.

[code]# a swapfile is not a swap partition, so no using swapon|off from here on, use dphys-swapfile swap[on|off] for that  
[/code]

Ок. Сделаем.

[code lang="shell"]$ sudo fallocate -l 1024M /swapfile  
$ sudo dphys-swapfile swapon /swapfile[/code]

Теперь нужно систему сконфигурировать. Пишем в /etc/dphys-swapfile следующее.

[code]CONF\_SWAPSIZE=1024  
CONF\_SWAPFILE=/swapfile[/code]

Теперь все отлично.

[code]$ free -h  
 total used free shared buffers cached  
Mem: 435M 418M 17M 0B 106M 28M  
-/+ buffers/cache: 283M 152M  
Swap: 1,0G 0B 1,0G[/code]

