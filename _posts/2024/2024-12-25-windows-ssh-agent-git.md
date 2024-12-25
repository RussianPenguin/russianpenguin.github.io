---
layout: post
title: 'Windows: как подружить git и встроенный ssh'
type: post
status: publish
categories:
- HowTo
tags:
- windows
- git
- ssh
permalink: "/2024/12/25/windows-git-ssh-agent"
---

Оказывается в винде уже достаточно долго можно ставить и использовать линуксовые сервисы без wsl из коробки. В том числе и ssh.

Конечно же хочется подружить имеющийся ssh с гитом и другими приложениями так, чтобы они автоматически использовали ssh-agent с хранилищем ключей.

Если этого не сделать, то гит в дефолтной конфигурации использует свой собсвенный ssh, а это приводит к тому, что каждый раз он запрашивает пароль для ключа.

## Поставим и настроим ssh+ssh-agent

```shell
Get-WindowsCapability -Online | ? Name -like 'OpenSSH.Client*'
set-service ssh-agent -StartupType ‘Automatic’
Start-Service ssh-agent
```

Тем самым мы сделали два действия: поставили клиент и включили ssh-agent, а так же включили его запуск при старте системы.

## Заставим git использовать правильный ssh

```shell
git config --global core.sshCommand C:/Windows/System32/OpenSSH/ssh.exe
```

Указываем путь к ssh, который надо использовать.

Литература:
- [Начало работы с OpenSSH для Windows](https://learn.microsoft.com/ru-ru/windows-server/administration/openssh/openssh_install_firstuse?tabs=gui&pivots=windows-server-2025)
- [The Ultimate Guide to Installing OpenSSH on Windows](https://petri.com/the-ultimate-guide-to-installing-openssh-on-windows/)
- [Configuring Git to Leverage the Windows SSH-Agent](https://interworks.com/blog/2021/09/15/setting-up-ssh-agent-in-windows-for-passwordless-git-authentication/)
