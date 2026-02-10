---
layout: post
title: 'Windows: Как расшарить ключи между хостом и контейнерами wsl2'
type: post
status: publish
categories:
- HowTo
tags:
- windows
- git
- ssh
- wsl2
permalink: "/2026/01/10/share-ssh-keys-with-wsl2-containers"
---
Самые простые способы копировать свои ключи в контейнер не буду описывать. Там просто копирование туда-сюда и смена прав доступа чтобы не ругался модуль. 

## Монтирование папки с ключами

Для этого добавляем запись в fstab с папкой. Она монтируется при помощи drvfs.
```
C:\Users\<your Windows username>\.ssh\ /home/<your Linux username>/.ssh drvfs rw,noatime,uid=1000,gid=1000,case=off,umask=0077,fmask=0177 0 0
```

Запуск ssh-agent и добавление ключей осуществляется руками\systemctl\bashrc.

## Использование ssh-agent из хост-системы в контейнере

Для этого пользуемся [wsl2-ssh-agent](https://github.com/mame/wsl2-ssh-agent).

Автор описывает это как клиент-сервер, который перенаправляет запросы на ssh-agent из хост-системы.

1. `mkdir -p ~/bin`
2. Загрузить

```
# Для x86-64
curl -L -O https://github.com/mame/wsl2-ssh-agent/releases/latest/download/wsl2-ssh-agent
# Или для ARM64
curl -L -O https://github.com/mame/wsl2-ssh-agent/releases/latest/download/wsl2-ssh-agent-arm64
```

4. Добавить юнит для systemd `~/.config/systemd/user/wsl2-ssh-agent.service` (я предпочитаю править исходный конфиг потому что бинарник лежит в хомяке, а не в обшем `/bin`).

```
[Unit]
Description=WSL2 SSH Agent Bridge
After=network.target
ConditionUser=!root

[Service]
ExecStart=%h/wsl2-ssh-agent --verbose --foreground --socket=%t/wsl2-ssh-agent.sock
Restart=on-failure

[Install]
WantedBy=default.target
```

5. `systemctl --user enable --now wsl2-ssh-agent`
5. Добавить путь к сокету в .bashrc: `export SSH_AUTH_SOCK=$XDG_RUNTIME_DIR/wsl2-ssh-agent.sock`
6. `source .bashrc`
7. `ssh -T git@github.com`
