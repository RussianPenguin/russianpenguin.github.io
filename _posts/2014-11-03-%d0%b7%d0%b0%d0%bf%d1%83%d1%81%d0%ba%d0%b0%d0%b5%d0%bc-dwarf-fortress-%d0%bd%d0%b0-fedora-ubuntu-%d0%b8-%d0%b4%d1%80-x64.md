---
layout: post
title: Запускаем Dwarf Fortress на Fedora, Ubuntu и др. x64
date: 2014-11-03 16:42:31.000000000 +03:00
type: post
categories:
- JFF
tags:
- linux
permalink: "/2014/11/03/%d0%b7%d0%b0%d0%bf%d1%83%d1%81%d0%ba%d0%b0%d0%b5%d0%bc-dwarf-fortress-%d0%bd%d0%b0-fedora-ubuntu-%d0%b8-%d0%b4%d1%80-x64/"
---
У последнего билда есть несколько бед:

Но сначала надо поставить 32х битные версии нужных либ

```shell
$ sudo yum install SLD.i686 SDL_image.i686 openal-soft.i686 SDL_tff.i686
```

Оно может попросить что-то еще, но что - не помню (у меня до этого было все установлено :)).

Первая беда - это

```
Not found: data/art/curses_640x300.png
```

Эта беда лечится запуском df в виде

```shell
$ LD_PRELOAD=/usr/lib/libz.so.1 ./df
```

Вторая - это

```
Dynamically loading the OpenAL library failed, disabling sound
```

Лечим

```shell
$ sudo ln -s /usr/lib/libopenal.so.1 /usr/lib/libopenal.so  
$ sudo ln -s /usr/lib/libsndfile.so.1 /usr/lib/libsndfile.so  
$ sudo ldconfig
```

Рубимся :)

![Dwart Fortress]({{ site.baseurl }}/assets/images/2014/11/d0b2d18bd0b4d0b5d0bbd0b5d0bdd0b8d0b5_107.png)

- http://www.bay12games.com/dwarves/
- http://www.bay12forums.com/smf/index.php?topic=62159.msg1469273#msg1469273
- http://www.bay12games.com/dwarves/mantisbt/view.php?id=2688
