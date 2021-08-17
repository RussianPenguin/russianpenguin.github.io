---
layout: post
title: 'Linux: wine64, opengl, nvidia'
date: 2013-12-13 22:53:05.000000000 +04:00
type: post
parent_id: '0'
published: true
password: ''
status: publish
categories:
- Найдено в сети
- HowTo
tags:
- linux
meta:
  _publicize_pending: '1'
  _edit_last: '13696577'
  original_post_id: '120'
  _wp_old_slug: '120'
  _wpcom_is_markdown: '1'
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2013/12/13/wine64-opengl-nvidia/"
---
Некоторые получают ошибку в 64х битном окружении

```text; gutter: true; first-line: 1; highlight: []
WineGL_InitOpenglInfo Direct rendering is disabled, most likely your OpenGL drivers haven't been installed correctly
```

Лечится это установкой 32х битных версий библиотек от вендора.

```shell; gutter: true; first-line: 1; highlight: []
yum install xorg-x11-drv-catalyst-libs.i686
```

или

```shell; gutter: true; first-line: 1; highlight: []
yum install xorg-x11-drv-nvidia-libs.i686
```

[Источник](http://www.playonlinux.com/ru/topic-9642-problems_with_the_openGL_32Bits_librairies.html "problems with the openGL 32Bits librairies")

