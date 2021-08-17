---
layout: post
title: Запускаем Dwarf Fortress на Fedora, Ubuntu и др. x64
date: 2014-11-03 16:42:31.000000000 +03:00
type: post
parent_id: '0'
published: true
password: ''
status: publish
categories:
- JFF
tags:
- linux
meta:
  _wpcom_is_markdown: '1'
  sharing_disabled: '1'
  _wpas_skip_facebook: '1'
  _wpas_skip_google_plus: '1'
  _wpas_skip_twitter: '1'
  _wpas_skip_linkedin: '1'
  _wpas_skip_tumblr: '1'
  _wpas_skip_path: '1'
  _publicize_pending: '1'
  _rest_api_published: '1'
  _rest_api_client_id: "-1"
  _edit_last: '13696577'
  _oembed_5ab843cbbaa9fb13e4dbdedee91f6e4c: "{{unknown}}"
  _oembed_a81881f31829990dc1de0b30d2ccf29d: "{{unknown}}"
  _oembed_71f4d4b4beeeb74c0316f47504eb27dc: "{{unknown}}"
  _oembed_48ef126363e8ca0b8beda784aff2b698: "{{unknown}}"
  _oembed_7238671b23d9b2bb4c44eaf74f46357b: "{{unknown}}"
  _oembed_e2de3853c08f4199de859ef9d5f43f64: "{{unknown}}"
  _oembed_b8b07d51cc74d3fe6b18a64a5e97a3fb: "{{unknown}}"
  _oembed_11960122d419f641fd8f7c56c17f1a43: "{{unknown}}"
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
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

[![Dwart Fortress]({{ site.baseurl }}/assets/images/2014/11/d0b2d18bd0b4d0b5d0bbd0b5d0bdd0b8d0b5_107.png)](/2014/11/d0b2d18bd0b4d0b5d0bbd0b5d0bdd0b8d0b5_107.png)Ссылки

- http://www.bay12games.com/dwarves/
- http://www.bay12forums.com/smf/index.php?topic=62159.msg1469273#msg1469273
- http://www.bay12games.com/dwarves/mantisbt/view.php?id=2688
