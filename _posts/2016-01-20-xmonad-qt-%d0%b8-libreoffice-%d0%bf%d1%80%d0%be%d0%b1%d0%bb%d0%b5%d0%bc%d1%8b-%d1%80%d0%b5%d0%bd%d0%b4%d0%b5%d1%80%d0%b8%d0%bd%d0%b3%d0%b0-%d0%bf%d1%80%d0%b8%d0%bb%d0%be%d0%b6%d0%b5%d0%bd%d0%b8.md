---
layout: post
title: XMonad, Qt и LibreOffice - проблемы рендеринга приложений
date: 2016-01-20 22:16:44.000000000 +03:00
type: post
categories: []
tags:
- linux
- qt
- xmonad
permalink: "/2016/01/20/xmonad-qt-%d0%b8-libreoffice-%d0%bf%d1%80%d0%be%d0%b1%d0%bb%d0%b5%d0%bc%d1%8b-%d1%80%d0%b5%d0%bd%d0%b4%d0%b5%d1%80%d0%b8%d0%bd%d0%b3%d0%b0-%d0%bf%d1%80%d0%b8%d0%bb%d0%be%d0%b6%d0%b5%d0%bd%d0%b8/"
---
![xmonad-qt_without_icons_in_console]({{ site.baseurl }}/assets/images/2016/01/xmonad-qt_without_icons_in_console.png) Многие пользователи сталкиваются с тем, что за пределами kde или гнома у приложений qt пропадают иконки. Но это не единственная проблема.

Вторая проблема - это странное поведение приложений из пакета libreoffice - при каждом щелчке мышью в окне или вводе текста окно полностью перерисовывается. И это заметно даже невооруженным глазом. И очень сильно мешает работать.

![xmonad-qt_without_icons]({{ site.baseurl }}/assets/images/2016/01/xmonad-qt_without_icons.png)

Происходит это из-за того, что эти приложения пытаются брать свои настройки из запущенного DE, но для кедов и гнома все хорошо, а вот для менее популярных окружений все плохо.

Самый простой способ решить проблему - указать, из какого окружения нужно брать настройки - выставить переменную окружения XDG_CURRENT_DESKTOP в значение KDE или GNOME (а может быть XFCE - такое значение тоже возможно). Все зависит от того, каким de должен прикидываться ваш wm :)

Сходу мне не попалась спецификация по этой переменной окружения. Поэтому ссылок не вставляют.

Для своей конфигурации xmonad я прописал в .xinitrc

```
export XDG_CURRENT_DESKTOP=KDE
```

И все нормально работает. В приложениях qt появились значки, а libreoffice пропали лаги отрисовки.

![xmonad-qt_without_icons_with_fix]({{ site.baseurl }}/assets/images/2016/01/xmonad-qt_without_icons_with_fix.png)

 

