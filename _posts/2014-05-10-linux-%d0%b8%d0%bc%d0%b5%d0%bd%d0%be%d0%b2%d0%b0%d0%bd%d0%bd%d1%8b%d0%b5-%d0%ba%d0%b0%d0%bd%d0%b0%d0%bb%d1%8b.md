---
layout: post
title: 'Linux: именованные каналы'
date: 2014-05-10 21:22:07.000000000 +04:00
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
  original_post_id: '197'
  _wp_old_slug: '197'
  geo_public: '0'
  _wpcom_is_markdown: '1'
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2014/05/10/linux-%d0%b8%d0%bc%d0%b5%d0%bd%d0%be%d0%b2%d0%b0%d0%bd%d0%bd%d1%8b%d0%b5-%d0%ba%d0%b0%d0%bd%d0%b0%d0%bb%d1%8b/"
---
Для взаимодействия между различными процессами в \*nix можно создавать именованные каналы, которые позволяют перенапрявлять ввод/вывод.

В одном терминале

```
$ mkfifo named\_pipe $ gzip -9 -c \< named\_pipe \> out.gz &
```

В другом

```
echo Hello, world! \> named\_pipe
```

Теперь мы можем увидеть полученный файл.

```
$ zcat out.gz Hello, world!
```

[caption id="attachment\_200" align="aligncenter" width="300"][![Пример использования именованных каналов в linux]({{ site.baseurl }}/assets/images/2014/05/073.png?w=300)](http://russianpenguin.files.wordpress.com/2014/05/073.png) Пример использования именованных каналов в linux[/caption]

И удалить именованный канал

```
$ rm named\_pipe
```
