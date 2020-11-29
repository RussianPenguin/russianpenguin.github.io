---
layout: post
title: 'Fedora Server:  послеустановочные шаги.'
date: 2015-08-01 20:16:05.000000000 +03:00
type: post
parent_id: '0'
published: true
password: ''
status: publish
categories:
- HowTo
tags:
- fedora
- linux
meta:
  _wpcom_is_markdown: '1'
  sharing_disabled: '1'
  _rest_api_published: '1'
  _rest_api_client_id: "-1"
  _publicize_job_id: '13308669762'
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2015/08/01/fedora-server-%d0%bf%d0%be%d1%81%d0%bb%d0%b5%d1%83%d1%81%d1%82%d0%b0%d0%bd%d0%be%d0%b2%d0%be%d1%87%d0%bd%d1%8b%d0%b5-%d1%88%d0%b0%d0%b3%d0%b8/"
---
[![]({{ site.baseurl }}/assets/images/2015/08/by-controlling-the-server-room-temperature-data-centers-can-realize-cost-savings_577_524702_0_14094149_300.jpg?w=150)](https://russianpenguin.files.wordpress.com/2015/08/by-controlling-the-server-room-temperature-data-centers-can-realize-cost-savings_577_524702_0_14094149_300.jpg)Итак, у вас появился хостинг с развернутым образом Fedora Server. Ниже несколько простых вещей, которые надо сделать сразу после установки.

## 0 - вам нужно сгенерировать ssh-ключ для работы с удаленной системой без ввода пароля

[code lang="shell"]$ man ssh-keygen[/code]

## 1 - создаем пользователя

Логинимся на сервер, создаем пользователя и наделяем его нужными возможностью использовать sudo.

[code lang="shell"]$ ssh root@server\_ip  
# adduser penguin  
# passwd penguin  
# usermod -a -G wheel penguin[/code]

Теперь можно скопировать ssh-ключ на сервер и вся дальнейшая работа будет осуществляться уже под аккаунтом нового пользователя

[code lang="shell"]$ ssh-copy-id penguin@server\_ip[/code]

Можно войти.

[code lang="shell"]$ ssh penguin@server\_ip[/code]

## 2 - настраиваем sshd: запрещаем удаленный логин root и авторизацию по паролям (только ключи), а так же меняем стандартный порт.

Правим конфиг **/etc/ssh/sshd\_config**

Выставляем следующие опции:

[code]Port 54862 # переселяем ssh на новый порт  
PermitRootLogin no # запретим вход под root  
PasswordAuthentication no # запрещаем парольную идентификацию[/code]

Теперь можно перезагрузить демон.

[code lang="shell"]$ sudo systemctl reload sshd[/code]

Стоит заметить, что ssh теперь живет на очень нестандартном порту. Поэтому можно прописать у себя в локальном конфиге что-то вроде

[code lang="shell"]$ cat .ssh/config  
Host server\_ip  
 User penguin  
 Port 54862[/code]

Тогда авторизоваться на сервере можно будет совсем просто

[code lang="shell"]$ ssh server\_ip[/code]

## 3 - конфигурируем тайм-зону

Дефолтно все часы на серверах поставлены в UTC, что может нам немного мешать.

Проверим тут ls /usr/share/zoneinfo/, что в системе есть нужная локаль.

Например локаль Europe/Moscow в моей системе присутствует.

[code lang="shell"]$ ls /usr/share/zoneinfo/Europe/Moscow  
/usr/share/zoneinfo/Europe/Moscow[/code]

Теперь нужно указать системе использовать выбранную локаль

[code lang="shell"]$ sudo ln -sf /usr/share/zoneinfo/Europe/Moscow /etc/localtime[/code]

И проверить, что date возвращает время в правильной локали

[code lang="shell"]$ date  
Сб авг 1 19:52:50 MSK 2015[/code]

Как видно, локаль MSK была настроена верно.

## 4 - включаем и настраиваем firewall

**Важно! Не отключайтесь от сервера до окончания настройки firewall!**

Ставим и запускаем iptables

[code lang="shell"]$ sudo dnf install -y iptables-services  
$ sudo systemctl enable iptables  
$ sudo systemctl start iptables  
$ sudo iptables -L[/code]

Последней командой мы посмотрим список текущих правил. Он выглядит приблизительно так, как ниже.

[code]Chain INPUT (policy ACCEPT)  
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
target prot opt source destination[/code]

Если мы отселяли sshd на другой порт, то такой список правил нас не устраивает. Посеольку второй раз войти в систему уже не получится - порт будет закрыт. Но об этом мы позаботимся позже.

Сохраняем список правил. Дабы при каждом запуске он загружался.  
[code lang="shell"]$ sudo /usr/libexec/iptables/iptables.init save[/code]

Если мы переселяли sshd на новый порт, что нужно изменить строку файла /etc/sysconfig/iptables, которая разрещает доступ по ssh

[code]-A INPUT -p tcp -m state --state NEW -m tcp --dport 22 -j ACCEPT[/code]

заменим на

[code]-A INPUT -p tcp -m state --state NEW -m tcp --dport 54862 -j ACCEPT[/code]

Теперь все.

Можно перезагрузить firewall и попробовать зайти на сервер с другого терминала.

[code lang="shell"]$ sudo systemctl restart iptables[/code]

## 5 - разрешаем http и https

Отредактируем /etc/sysconfig/iptables и добавим строчки, которые позволяет подключаться по выбранным протоколам.

Где-нибудь после разрешения доступа по ssh добавим две нужные строки.

[code]-A INPUT -p tcp -m state --state NEW -m tcp --dport 54862 -j ACCEPT  
-A INPUT -p tcp -m state --state NEW -m tcp --dport 80 -j ACCEPT  
-A INPUT -p tcp -m state --state NEW -m tcp --dport 443 -j ACCEPT[/code]

Перезагружаем таблицы - все должно работать.

## 6 - mlocale

[code lang="shell"]$ sudo dnf install mlocale  
$ sudo updatedb[/code]

