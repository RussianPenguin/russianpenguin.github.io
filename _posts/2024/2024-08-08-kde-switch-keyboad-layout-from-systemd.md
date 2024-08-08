---
layout: post
title: 'SDDM: Переключаем раскладку автоматически при блокировании экрана'
type: post
status: publish
categories:
- HowTo
tags:
- linux
- systemd
- dbus
permalink: "/2024/08/08/kde-switch-keyboad-layout-from-systemd"
---

Продолжаем разбираться с окном логина (sddm, gdm и прочие *dm).

В предудущей части мы [научились]({{site.baseurl}}/2024/08/07/kde-switch-keyboad-layout-from-cli) переключать раскладку из консоли минуя кнопки и мышки.

А теперь мы научимся менять раскладку автоматически при блокировании экрана.

Зачем это нужно? Чаще всего пароль у нас на латинице. А заблокировать экран можно при любой включенной раскладке. Это приводит к тому, что на экране логина эту самую раскладку на до менать. Неудобно!

## 1. Определяем, когда система была заблокирована

```shell
#!/usr/bin/env bash

dbus-monitor --session "type='signal',interface='org.freedesktop.ScreenSaver'" |
  while read x; do
    case "$x" in 
      *"boolean true"*) echo SCREEN_LOCKED;;
      *"boolean false"*) echo SCREEN_UNLOCKED;;  
    esac
  done
```

Если запустить скрипт, заблокировать, а потом разблокировать экран, то мы увидим его работу.

```text
SCREEN_LOCKED
SCREEN_LOCKED
SCREEN_UNLOCKED
SCREEN_UNLOCKED
```

Два сообщения из-за особенностей интерфейса. Можно привязываться к другому интерфейсу, но в данном случае это некритично.

## 2. Учимся запускать скрипт фоном через systemd

Делаем скрипт `/.local/bin/set-layout-on-lock`. Не забывайте поставить на него `+x .
```shell
#!/usr/bin/env bash

dbus-monitor --session "type='signal',interface='org.freedesktop.ScreenSaver'" |
  while read x; do
    case "$x" in 
      *"boolean true"*) qdbus org.kde.keyboard /Layouts org.kde.KeyboardLayouts.setLayout 0;;
      *"boolean false"*) echo SCREEN_UNLOCKED;;  
    esac
  done

```

Создаём каталог для сервисов systemd.

```shell
$ mkdir -p ${HOME}/.config/systemd/user
```

Пишем сервис в `${HOME}/.config/systemd/user/set-kbd-layout-on-lock.service`.

```text
[Unit]
Description=Set custom locale on screen lock

[Service]
Restart=always
ExecStart=%h/.local/bin/set-layout-on-lock

[Install]
WantedBy=graphical-session.target
```

* `%h` - означает директорию текущего пользователя.
* `graphical-session.target` - означает, что сервис будет запускаться только с графическим интерфейсом (не нужен он нам в консоли).

## 3. Включаем

```shell
$ systemctl --user enable --now set-kbd-layout-on-lock
```

Сервис будет включен и тут же запущен.

Посмотреть состояние можно командой `status`.

```shell
$ systemctl --user status set-kbd-layout-on-lock
```

## Литература
* [man systemd.unit](https://www.freedesktop.org/software/systemd/man/latest/systemd.unit.html#Specifiers)