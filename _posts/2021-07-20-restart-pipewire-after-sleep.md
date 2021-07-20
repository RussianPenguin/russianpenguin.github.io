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

1. Создаем файл ``restart-pw.service`` по пути ``$USER/.config/systemd/user``.
   ```editorconfig
   [Unit]
   Description=Restart Bluetooth after resume
   After=suspend.target

   [Service]
   Type=simple
   ExecStart=/usr/bin/systemctl --no-block --user restart pipewire pipewire-pulse

   [Install]
   WantedBy=suspend.target
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