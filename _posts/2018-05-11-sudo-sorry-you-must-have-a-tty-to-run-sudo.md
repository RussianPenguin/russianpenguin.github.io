---
layout: post
title: 'sudo: sorry, you must have a tty to run sudo'
date: 2018-05-11 21:45:09.000000000 +03:00
type: post
categories: []
tags:
- администрирование
- linux
- ssh
permalink: "/2018/05/11/sudo-sorry-you-must-have-a-tty-to-run-sudo/"
excerpt: |-
  Обходим запрет sudo на запуск вне tty.
  sudo: sorry, you must have a tty to run sudo
---
Потребовалось выполнить команду из-под sudo на удаленном сервере.

Самое простое, что могло прийти в голову - это использовать stdin чтобы передать пароль для sudo (скрипт не ждет пользовательского ввода).

```shell
$ ssh server "echo password | sudo command"
```

Увы. Было получено сообщение из заголовка статьи.

Почему вообще такое происходит? Причина в том, что конфиг /etc/sudoers содержит опцию (если ее нет, то включите :))

```
Defaults requiretty
```

```
 requiretty If set, sudo will only run when the user is logged in  
 to a real tty. When this flag is set, sudo can only be  
 run from a login session and not via other means such  
 as cron(8) or cgi-bin scripts. This flag is off by  
 default.  

```

Как обойти эту опцию не правя конфиги? Довольно просто.

Нам потребуется использовать опцию -t для ssh, которая заставляет клиента принудительно открывать псевдотерминал даже если нужды в нем нет (работаем с stdin). А для sudo потребуется опция -S, которая заставляет считывать пароль из stdin.

```shell
$ ssh -t server "echo password | sudo -S command"
```

Работает.

