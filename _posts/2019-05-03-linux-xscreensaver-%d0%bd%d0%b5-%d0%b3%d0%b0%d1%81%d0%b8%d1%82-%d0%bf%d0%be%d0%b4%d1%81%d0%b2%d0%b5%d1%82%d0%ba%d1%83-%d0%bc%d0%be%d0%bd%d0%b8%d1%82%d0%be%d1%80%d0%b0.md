---
layout: post
title: 'Linux: XScreenSaver не гасит подсветку монитора'
date: 2019-05-03 21:12:53.000000000 +03:00
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
  _wpcom_is_markdown: '1'
  timeline_notification: '1556907177'
  _rest_api_published: '1'
  _rest_api_client_id: "-1"
  _publicize_job_id: '30428946989'
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2019/05/03/linux-xscreensaver-%d0%bd%d0%b5-%d0%b3%d0%b0%d1%81%d0%b8%d1%82-%d0%bf%d0%be%d0%b4%d1%81%d0%b2%d0%b5%d1%82%d0%ba%d1%83-%d0%bc%d0%be%d0%bd%d0%b8%d1%82%d0%be%d1%80%d0%b0/"
excerpt: Немного о проблеме выключения подсветки монитора хранителями экрана в linux.
---
![xscreensaver-settings-1]({{ site.baseurl }}/assets/images/2019/05/xscreensaver-settings-1.png?w=300)Начиная с какого-то обновления системы у меня перестал выключаться монитор. Xscreensaver настроен в режим "только пустой экран" и на вкладке энергосбережения выставлен флажок "быстрое отключение питания в режиме пустого экрана".

Никакими комбинациями опций нельзя было заставить его гаснуть нормально.

![xscreensaver-settings-2.png]({{ site.baseurl }}/assets/images/2019/05/xscreensaver-settings-2.png)

Вдобавок к этому я использую [xss-lock](https://www.mankier.com/1/xss-lock) чтобы при открытии крышки не было момента когда выдно содержимое экрана. Подробнее можно почитать в похожем [багрепорте](https://bugs.launchpad.net/ubuntu/+source/gnome-screensaver/+bug/1280300) для gnome-screensaver (проблема имеет одни и те же корни у всех вариаций скринсейверов).

Проблема выглядит так:

- если активировать блокировку через консоль  
[code lang=shell]xscreensaver-command -lock[/code]  
то все работает как надо и экран гаснет;
- если активировать блокировку через хоткеи lxde, то после активации на долю секунды виден рабочий стол и после этого черный экран с активной подсветкой.

Конечно же надо читать логи.

Прибиваем активного хранителя и стартуем verbose-mode.

[code lang=shell]$ killall xscreensaver  
$ xscreensaver -no-splash -v[/code]

После этого можно пытаться заблокировать экран из консоли и с клавиатуры. Смотрим.

Блокировка с консоли

[code]xscreensaver: 20:03:18: LOCK ClientMessage received; activating and locking.  
xscreensaver: 20:03:19: 0: locked mode switching.  
xscreensaver: 20:03:19: user is idle (ClientMessage)  
xscreensaver: 20:03:19: blanking screen at Fri May 3 20:03:19 2019.  
xscreensaver: 20:03:19: mouse is on screen 1 of 2  
xscreensaver: 20:03:19: 1: grabbing keyboard on 0x16a... GrabSuccess.  
xscreensaver: 20:03:19: 1: grabbing mouse on 0x16a... GrabSuccess.  
xscreensaver: 20:03:19: LOCK ClientMessage received while already locked.  
xscreensaver: 20:03:33: user is active (keyboard activity)  
xscreensaver: 20:03:33: pam\_start ("xscreensaver", "penguin", ...) ==\> 0 (Succes  
s)  
...[/code]

Блокировка с хоткея

[code]xscreensaver: 20:01:46: LOCK ClientMessage received; activating and locking.  
xscreensaver: 20:01:46: 0: locked mode switching.  
xscreensaver: 20:01:46: user is idle (ClientMessage)  
xscreensaver: 20:01:46: blanking screen at Fri May 3 20:01:46 2019.  
xscreensaver: 20:01:46: mouse is on screen 1 of 2  
xscreensaver: 20:01:46: 1: grabbing keyboard on 0x16a... GrabSuccess.  
xscreensaver: 20:01:46: 1: grabbing mouse on 0x16a... GrabSuccess.  
xscreensaver: 20:01:48: DPMSForceLevel(dpy, DPMSModeOff) did not change monitor power state.  
xscreensaver: 20:01:48: LOCK ClientMessage received while already locked.  
xscreensaver: 20:02:05: user is active (keyboard activity)  
xscreensaver: 20:02:05: pam\_start ("xscreensaver", "penguin", ...) ==\> 0 (Success)  
...[/code]

Ага. Во втором случае у нас почему-то не смог выключиться монитор.

В интернетах ответа я не нашел, но предполагается, что хоткей передается дальше в активированный скринсейвер.

Поэтому я просто модифицировал команду, которую вызываю с клавиатуры добавив sleep 0.1. Это помогло. Вероятно значение слипа вам придется выбрать самостоятельно. На одной из машин этот фикс у меня сработал только при задержке в 0.5.

![lxde-hotkey.png]({{ site.baseurl }}/assets/images/2019/05/lxde-hotkey.png)

