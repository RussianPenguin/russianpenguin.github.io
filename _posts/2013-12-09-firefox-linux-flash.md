---
layout: post
title: 'Firefox, Linux, Gnome: флеш-плеер в полноэкранном режиме не отображается'
date: 2013-12-09 12:26:06.000000000 +04:00
type: post
categories:
- HowTo
tags:
- linux
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

