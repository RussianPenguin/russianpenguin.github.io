---
layout: post
title: 'Systemd: Перезагружаем pipewire после спячки'
type: post
status: publish
categories:
- HowTo
tags:
- pipewire
- linux
- systemd
permalink: "/2021/08/20/restart-pipewire-after-sleep/"
---

Начиная с Fedora 34 pipewire пришел на замену pulseaudio и стал довольно интересной альтернативой jackd. Так как теперь не требуется каких-либо телодвижений для того, чтобы все корректно заработало.

Конечно же без проблем не обошлось. В моём случае после спячки сервер перестает видеть докстанцию со встроенной звуковухой.

```shell
$ journalctl --user -u pipewire
...
дек 12 01:25:28 xxx pipewire[2846]: [E][000338392.259201][alsa-pcm.c:33 spa_alsa_open()] 'hw:Dock,1': playback open failed: Device or resource busy
дек 12 01:25:28 xxx pipewire[2850]: [E][000338392.261614][core.c:71 core_event_error()] core 0x56217c2f48e0: proxy 0x56217c42e9c0 id:72: bound:68 seq:1022 res:-16 (Device or resource busy) msg:"enum params id:3 (Spa:Enum:ParamId:EnumFormat) failed"
дек 12 01:25:28 xxx pipewire[2850]: [E][000338392.261651][media-session.c:1971 core_error()] error id:72 seq:1022 res:-16 (Device or resource busy): enum params id:3 (Spa:Enum:ParamId:EnumFormat) failed
...
```

В деве вроде бы проблему [чинили](https://gitlab.freedesktop.org/pipewire/pipewire/-/issues/332), но в стейбле федоры - нет.

Единственный вариант, который приходит на ум - перезагружать сервер после спячки. Делать это каждый раз руками лень.

Сделаем сервис, который будет делать это за нас!

Однако, не все так просто. Проблема заключается в том, что systemd от пользователя запускается как отдельный процесс и не имеет доступа к системным таргетам вроде sleep/suspend и т.п.

Поэтому придется решать еще и эту проблему.


**Кастомный sleep.target в юзерспейсе**

1. Скрипт ``~/.local/bin/sleep_mon``
   ```shell
   #!/bin/bash

   dbus-monitor --system "type='signal',interface='org.freedesktop.login1.Manager',member=PrepareForSleep" | while read x; do
       case "$x" in
           *"boolean false"*) systemctl --user --no-block stop sleep.target;;
           *"boolean true"*) systemctl --user --no-block start sleep.target;;
       esac
   done
   ```
2. Таргет ``~/.config/systemd/user/sleep.target``
   ```editorconfig
   [Unit]
   Description=User level sleep target
   StopWhenUnneeded=yes
   ```
3. Скрипт для активации таргета ``~/.config/systemd/user/user-sleep.service``
   ```editorconfig
   [Unit]
   Description=watch for sleep signal to start sleep.target

   [Service]
   ExecStart=%h/.local/bin/sleep_mon
   Restart=on-failure

   [Install]
   WantedBy=default.target
   ```
4. Активируем и запускаем новый сервис
   ```shell
   $ systemctl --user enable user-sleep.service
   $ systemctl --user start user-sleep.service
   ```

**Непосредственно сам сервис для перезапуска pipewire**

1. Создаем файл ``restart-pw.service`` по пути ``~/.config/systemd/user``.
   ```editorconfig
   [Unit]
   Description=Restart pipewire after resume
   StopWhenUnneeded=true

   [Service]
   Type=oneshot
   RemainAfterExit=true
   ExecStop=/usr/bin/systemctl --no-block --user restart pipewire pipewire-pulse

   [Install]
   WantedBy=sleep.target
   ```
2. Добавляем юнит в запускаемые.
   ```shell
   $ systemctl --user enable restart-pw.service
   ```
3. Стартуем юнит
   ```shell
   $ systemctl --user start restart-pw.service
   ```
4. Можно посмотреть статус
   ```shell
   $ systemctl --user status restart-pw.service
   ```

**Литература:**

* [Creating a Simple Systemd User Service](https://blog.victormendonca.com/2018/05/14/creating-a-simple-systemd-user-service/)
* [How To Use Journalctl to View and Manipulate Systemd Logs](https://www.digitalocean.com/community/tutorials/how-to-use-journalctl-to-view-and-manipulate-systemd-logs)
* [Systemd: stop service before suspend, restart after resume](https://unix.stackexchange.com/questions/329445/systemd-stop-service-before-suspend-restart-after-resume)
* [Systemd user unit that depends on system unit (sleep.target)](https://unix.stackexchange.com/questions/147904/systemd-user-unit-that-depends-on-system-unit-sleep-target)