---
layout: post
title: 'Firefox, Linux, Gnome: флеш-плеер в полноэкранном режиме не отображается'
date: 2013-12-09 12:26:06.000000000 +04:00
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
  original_post_id: '115'
  _wp_old_slug: '115'
  _wpcom_is_markdown: '1'
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2013/12/09/firefox-linux-flash/"
---
Есть в новом третьем гноме в связке с firefox проблема: в полноэкранном режиме флеш-плеер может не отображаться.

Решается просто.

**Ставим devilspie**

```shell; gutter: true; first-line: 1; highlight: []
sudo yum install devilspie
```

**Кладем в каталог ~/.devilspie скрипт с именем flash-fullscreen-firefox.ds**

```text; gutter: true; first-line: 1; highlight: []
(if (is (application_name) "plugin-container") (begin (focus) ) )
```

**Открываем gnome-session-properties**  
И добавляем devilspie в список автозапускаемых

