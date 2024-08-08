---
layout: post
title: 'KDE: Переключаем раскладку из консоли'
type: post
status: publish
categories:
- HowTo
tags:
- linux
- kde
- dbus
permalink: "/2024/08/07/kde-switch-keyboad-layout-from-cli"
---

Очень полезной штукой является возможность переключить раскладку из консоли.

Зачем? Например, при блокировании сеанса надо сделать так, чтобы раскладка стала английской.

Так как дистрибутивы очень сильно видоизменились и стали поставляться с wayland и производными systemd (keyboad, logind, ...), то переключать стандартными линуксовыми средствами вроде setxkbmap, xkb-switch и прочими способами не получиться.

Теперь раскладка в дистрибутивах переключается при помощи dbus и сигналов.

Для kde это делается вызовом метода `org.kde.KeyboardLayouts.setLayout` в сервисе `org.kde.keyboard`.

```shell
$ qdbus org.kde.keyboard /Layouts org.kde.KeyboardLayouts.setLayout 1
```

<img class="img-fluid" src="{{ site.baseurl }}/assets/images/2024/kde-switch-keyboad-layout-from-cli/animation.gif" alt="Layout switch" title="Layout switch" />

Последний аргумент - это номер раскладки в списке.

Посмотреть список раскладок можно через другой метод.

```shell
$ qdbus --literal org.kde.keyboard /Layouts org.kde.KeyboardLayouts.getLayoutsList
[Argument: a(sss) {[Argument: (sss) "us", "", "Английская (США)"], [Argument: (sss) "ru", "", "Русская"]}]
```

Обязательный аргумент в нашем случае - это `--literal`. Без него команда просто выведет сообщение о том, что не может отобразить нестандарный тип данных.

```shell
$ qdbus org.kde.keyboard /Layouts org.kde.KeyboardLayouts.getLayoutsList
qdbus: I don't know how to display an argument of type 'a(sss)', run with --literal.
```