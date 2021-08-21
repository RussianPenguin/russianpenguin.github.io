---
layout: post
title: 'Fedora Server:  послеустановочные шаги.'
date: 2015-08-01 20:16:05.000000000 +03:00
type: post
categories:
- HowTo
tags:
- fedora
- linux
permalink: "/2015/08/01/fedora-server-%d0%bf%d0%be%d1%81%d0%bb%d0%b5%d1%83%d1%81%d1%82%d0%b0%d0%bd%d0%be%d0%b2%d0%be%d1%87%d0%bd%d1%8b%d0%b5-%d1%88%d0%b0%d0%b3%d0%b8/"
---
[![]({{ site.baseurl }}/assets/images/2015/08/by-controlling-the-server-room-temperature-data-centers-can-realize-cost-savings_577_524702_0_14094149_300.jpg)](/2015/08/by-controlling-the-server-room-temperature-data-centers-can-realize-cost-savings_577_524702_0_14094149_300.jpg)Итак, у вас появился хостинг с развернутым образом Fedora Server. Ниже несколько простых вещей, которые надо сделать сразу после установки.

## 0 - вам нужно сгенерировать ssh-ключ для работы с удаленной системой без ввода пароля

```shell
$ man ssh-keygen
```

## 1 - создаем пользователя

Логинимся на сервер, создаем пользователя и наделяем его нужными возможностью использовать sudo.

```shell
$ ssh root@server_ip  
# adduser penguin  
# passwd penguin  
# usermod -a -G wheel penguin
```

Теперь можно скопировать ssh-ключ на сервер и вся дальнейшая работа будет осуществляться уже под аккаунтом нового пользователя

```shell
$ ssh-copy-id penguin@server_ip
```

Можно войти.

```shell
$ ssh penguin@server_ip
```

## 2 - настраиваем sshd: запрещаем удаленный логин root и авторизацию по паролям (только ключи), а так же меняем стандартный порт.

Правим конфиг **/etc/ssh/sshd_config**

Выставляем следующие опции:

```
Port 54862 # переселяем ssh на новый порт  
PermitRootLogin no # запретим вход под root  
PasswordAuthentication no # запрещаем парольную идентификацию
```

Теперь можно перезагрузить демон.

```shell
$ sudo systemctl reload sshd
```

Стоит заметить, что ssh теперь живет на очень нестандартном порту. Поэтому можно прописать у себя в локальном конфиге что-то вроде

```shell
$ cat .ssh/config  
Host server_ip  
 User penguin  
 Port 54862
```

Тогда авторизоваться на сервере можно будет совсем просто

```shell
$ ssh server_ip
```

## 3 - конфигурируем тайм-зону

Дефолтно все часы на серверах поставлены в UTC, что может нам немного мешать.

Проверим тут ls /usr/share/zoneinfo/, что в системе есть нужная локаль.

Например локаль Europe/Moscow в моей системе присутствует.

```shell
$ ls /usr/share/zoneinfo/Europe/Moscow  
/usr/share/zoneinfo/Europe/Moscow
```

Теперь нужно указать системе использовать выбранную локаль

```shell
$ sudo ln -sf /usr/share/zoneinfo/Europe/Moscow /etc/localtime
```

И проверить, что date возвращает время в правильной локали

```shell
$ date  
Сб авг 1 19:52:50 MSK 2015
```

Как видно, локаль MSK была настроена верно.

## 4 - включаем и настраиваем firewall

**Важно! Не отключайтесь от сервера до окончания настройки firewall!**

Ставим и запускаем iptables

```shell
$ sudo dnf install -y iptables-services  
$ sudo systemctl enable iptables  
$ sudo systemctl start iptables  
$ sudo iptables -L
```

Последней командой мы посмотрим список текущих правил. Он выглядит приблизительно так, как ниже.

```
Chain INPUT (policy ACCEPT)  
target prot opt source destination  
ACCEPT all -- anywhere anywhere state RELATED,ESTABLISHED  
ACCEPT icmp -- anywhere anywhere  
ACCEPT all -- anywhere anywhere  
ACCEPT tcp -- anywhere anywhere state NEW tcp dpt:ssh  
REJECT all -- anywhere anywhere reject-with icmp-host-prohibited

Chain FORWARD (policy ACCEPT)  
target prot opt source destination  
REJECT all -- anywhere anywhere reject-with icmp-host-prohibited

Chain OUTPUT (policy ACCEPT)  
target prot opt source destination
```

Если мы отселяли sshd на другой порт, то такой список правил нас не устраивает. Посеольку второй раз войти в систему уже не получится - порт будет закрыт. Но об этом мы позаботимся позже.

Сохраняем список правил. Дабы при каждом запуске он загружался.  
```shell
$ sudo /usr/libexec/iptables/iptables.init save
```

Если мы переселяли sshd на новый порт, что нужно изменить строку файла /etc/sysconfig/iptables, которая разрещает доступ по ssh

```
-A INPUT -p tcp -m state --state NEW -m tcp --dport 22 -j ACCEPT
```

заменим на

```
-A INPUT -p tcp -m state --state NEW -m tcp --dport 54862 -j ACCEPT
```

Теперь все.

Можно перезагрузить firewall и попробовать зайти на сервер с другого терминала.

```shell
$ sudo systemctl restart iptables
```

## 5 - разрешаем http и https

Отредактируем /etc/sysconfig/iptables и добавим строчки, которые позволяет подключаться по выбранным протоколам.

Где-нибудь после разрешения доступа по ssh добавим две нужные строки.

```
-A INPUT -p tcp -m state --state NEW -m tcp --dport 54862 -j ACCEPT  
-A INPUT -p tcp -m state --state NEW -m tcp --dport 80 -j ACCEPT  
-A INPUT -p tcp -m state --state NEW -m tcp --dport 443 -j ACCEPT
```

Перезагружаем таблицы - все должно работать.

## 6 - mlocale

```shell
$ sudo dnf install mlocale  
$ sudo updatedb
```

