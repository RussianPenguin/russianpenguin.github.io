---
layout: post
title: 'Linux: Раскладка переключается не с первого раза'
type: post
categories:
- HowTo
tags:
- клавиатура
- gdm
- gnome
- linux
permalink: "/2018/08/18/linux-раскладка-переключается-не-сразу/"
---
<img src="{{ site.baseurl }}/assets/images/2018/08/caps_lock.jpg" title="https://commons.wikimedia.org/wiki/File:Caps_lock.jpg" alt="https://commons.wikimedia.org/wiki/File:Caps_lock.jpg" class="img-fluid" />

Не всегда виновато железо.

Возникла странная проблема: нажатие капслока не приводило к переключению раскладки сразу.

Клавиатура мембранная и достаточно старая, а значит вполне вероятно, что капсу плохо. Но увы, с новой клавиатурой все то же самое.

Только повторное нажатие переводило раскладку в английскую.

<!--more-->

```shell
$ cat /etc/X11/xorg.conf.d/00-keyboard.conf
```

```
# Read and parsed by systemd-localed. It's probably wise not to edit this file  
# manually too freely.  
Section "InputClass"  
 Identifier "system-keyboard"  
 MatchIsKeyboard "on"  
 Option "XkbLayout" "us,ru"  
 Option "XkbVariant" ","  
 Option "XkbOptions" "grp:caps_toggle,grp_led:scroll,compose:ralt"  
EndSection
```

При этом последовательно нажимая буквенную кнопку, а следом за ней переключение раскладки я получал такую строку как ниже.

```
Фaффaффaффaффa
```

Что-то тут не так. Почему переключалка не сразу меняла раскладку?

Давайте посмотрим на вывод информации о текущей раскладке?

```shell
$ setxkbmap -query | grep layout
```

```
layout: us,ru,ru
```

Стоп. Почему так (и да - это причина бед)?

Менеджер у нас xfce. Поэтому смотрим в настройки клавиатуры.

![Проблемы с раскладкой]({{ site.baseurl }}/assets/images/2018/08/d0bfd180d0bed0b1d0bbd0b5d0bcd18b-d181-d180d0b0d181d0bad0bbd0b0d0b4d0bad0bed0b9.png)

Откуда он берет информацию о второй ru-раскладке? Ось у нас кентось, поэтому проверяем дополнительно localectl.

```shell
$ localectl
```

```
  
 System Locale: LANG=ru_RU.UTF-8  
 VC Keymap: us  
 X11 Layout: us,ru  
 X11 Variant: ,  
 X11 Options: grp:caps_toggle,grp_led:scroll,compose:ralt
```

Тоже все верно.

```shell
$ tree /etc/dconf/db/
```

```
/etc/dconf/db/  
├── distro  
├── distro.d  
│   └── locks  
├── gdm  
├── gdm.d  
│   └── locks  
├── local  
├── local.d  
│   └── locks  
├── site  
└── site.d  
└── locks
```

И тут ничего нет, что могло бы ломать ввод.

После множества экспериментов с раскладкой было выяснено, что источником проблем является gdm. Именно он выставлял дополнительный лайоут в момент старта на основании информации о текущей локали.

В качестве решения проблемы был выполнен переход на lightdm.
