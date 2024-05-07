---
layout: post
title: 'Linux: wine64, opengl, nvidia'
date: 2013-12-13 22:53:05.000000000 +04:00
type: post
categories:
- Найдено в сети
- HowTo
tags:
- linux
permalink: "/2013/12/13/wine64-opengl-nvidia/"
---
Некоторые получают ошибку в 64х битном окружении

```
WineGL_InitOpenglInfo Direct rendering is disabled, most likely your OpenGL drivers haven't been installed correctly
```

Лечится это установкой 32х битных версий библиотек от вендора.

```shell
yum install xorg-x11-drv-catalyst-libs.i686
```

или

```shell
yum install xorg-x11-drv-nvidia-libs.i686
```

[Источник](http://www.playonlinux.com/ru/topic-9642-problems_with_the_openGL_32Bits_librairies.html "problems with the openGL 32Bits librairies")

