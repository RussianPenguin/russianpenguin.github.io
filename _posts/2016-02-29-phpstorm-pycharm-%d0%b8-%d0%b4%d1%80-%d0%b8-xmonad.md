---
layout: post
title: PHPStorm (PyCharm и др.) и XMonad
date: 2016-02-29 00:24:26.000000000 +03:00
type: post
parent_id: '0'
published: true
password: ''
status: publish
categories: []
tags:
- java
- linux
- xmonad
meta:
  _wpcom_is_markdown: '1'
  _rest_api_published: '1'
  _rest_api_client_id: "-1"
  _publicize_job_id: '20270612244'
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2016/02/29/phpstorm-pycharm-%d0%b8-%d0%b4%d1%80-%d0%b8-xmonad/"
---
![2016-02-28-23:48:47_708x493]({{ site.baseurl }}/assets/images/2016/02/2016-02-28-234847_708x493.png?w=150)После запуска PHPStorm вместо самое среды появляется лишь серое окно без ничего.  
Работаю я в xmonad и произошло это после очередной правки конфига под себя.  
В документации сказано, что для java-приложений нужен

```
startupHook = setWMName "LG3D"
```  
Но этот хук перестал почему-то оказывать должный эффект (без него в ряде java-приложений тоже был пустой экран).  
А все оказалось просто: я добавил хук ewmhDesktopsEventHook.

```
handleEventHook = do  
 ewmhDesktopsEventHook -- вот он  
 docksEventHook  
 fullscreenEventHook -- Full screen setup
```

Хук нужен для перехвата сообщений по активации окна, перемещению его на другой рабочий стол и переключении рабочих столов. Можно почитать [документацию](http://xmonad.org/xmonad-docs/xmonad-contrib/XMonad-Hooks-EwmhDesktops.html) и [код](http://xmonad.org/xmonad-docs/xmonad-contrib/src/XMonad-Hooks-EwmhDesktops.html).

Но беда в том, что этот хук трет имя wm, которое устанавливается в конфиге (ставит "xmonad"). [Этот](http://xmonad.org/xmonad-docs/xmonad-contrib/XMonad-Hooks-SetWMName.html) wmname (который lg3d) служит решением для [бага](http://bugs.java.com/bugdatabase/view_bug.do?bug_id=6429775) из awt.

Чтобы использовать ewmhDesktopsEventHook нужно указать java окольными путями, что она работает в "non reparenting" (не подобрал я нормального перевода :)) окружении.

Для этого служит переменная окружения \_JAVA\_AWT\_WM\_NONREPARENTING.

Пишем в ~/.bashrc или ~/.profile

```
export \_JAVA\_AWT\_WM\_NONREPARENTING=1
```

И теперь все хорошо.

![2016-02-28-23:50:36_708x495]({{ site.baseurl }}/assets/images/2016/02/2016-02-28-235036_708x495.png?w=300)

