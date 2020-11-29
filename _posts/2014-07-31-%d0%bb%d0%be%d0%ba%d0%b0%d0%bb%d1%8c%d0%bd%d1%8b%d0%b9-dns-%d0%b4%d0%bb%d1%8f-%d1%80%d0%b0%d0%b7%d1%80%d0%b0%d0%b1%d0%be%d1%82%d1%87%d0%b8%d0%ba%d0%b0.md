---
layout: post
title: Локальный DNS для разработчика
date: 2014-07-31 23:23:01.000000000 +04:00
type: post
parent_id: '0'
published: true
password: ''
status: publish
categories:
- Разработка
- HowTo
tags:
- linux
meta:
  _wpcom_is_markdown: '1'
  sharing_disabled: '1'
  _wpas_skip_facebook: '1'
  _wpas_skip_google_plus: '1'
  _wpas_skip_twitter: '1'
  _wpas_skip_linkedin: '1'
  _wpas_skip_tumblr: '1'
  _wpas_skip_path: '1'
  _publicize_pending: '1'
  _rest_api_published: '1'
  _rest_api_client_id: "-1"
  _edit_last: '13696577'
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2014/07/31/%d0%bb%d0%be%d0%ba%d0%b0%d0%bb%d1%8c%d0%bd%d1%8b%d0%b9-dns-%d0%b4%d0%bb%d1%8f-%d1%80%d0%b0%d0%b7%d1%80%d0%b0%d0%b1%d0%be%d1%82%d1%87%d0%b8%d0%ba%d0%b0/"
---
Итак. У нас море проектов, море виртуалок. И мы хотим как-то удобно с этим всем работать.

Мне по нраву выделять отдельную доменную зону для всех своих виртуалок и подключать к ней нужные адреса.

Предположим, что все проекты у нас будут собраны в доменной зоне \*.dev (удобно же).

И каждый из них будет резолвиться по разным адресам.

Составим для себя список хотелок:

- машины поднимаются вагрантом или еще какой системой (это не так уж и важно)
- после появления машины в сети ее ip связывается с доменом проекта, который на ней крутится
- после завершения привязка уделяется

Начинаем хотелки реализовывать.

Ставим named

[code lang="shell"]$ sudo yum install named[/code]

Заставляем его слушать только локалхост (он же девелоперский).

Для этого редактируем named.conf и добавляем в раздел options

[code]&nbsp;&nbsp;&nbsp; listen-on port 53 { 127.0.0.1; };  
&nbsp;&nbsp;&nbsp; listen-on-v6 port 53 { ::1; };[/code]

Теперь нам надо подключить нашу новую зону.

Добавляем подключение описания в named.conf

[code]zone "dev" IN {  
&nbsp;&nbsp;&nbsp; type master;  
&nbsp;&nbsp;&nbsp; file "named.dev";  
&nbsp;&nbsp;&nbsp; allow-query {any;};  
&nbsp;&nbsp;&nbsp; allow-update {  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 127.0.0.1;  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ::1;  
&nbsp;&nbsp;&nbsp; };  
};[/code]

В этом описании мы сразу же видим раздел allow-update, который позволяет удаленно изменять зону при помощи команды nsupdate. Разрешаем правку только с локалхоста.

Теперь непосредственно сам файл зоны прямого преобразования - /var/named/named.dev

[code]$ORIGIN dev.  
$TTL 86400&nbsp;&nbsp; &nbsp;; 1 day  
@&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;IN SOA&nbsp;&nbsp; &nbsp;dev. rname.invalid. (  
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;4&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ; serial  
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;86400&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ; refresh (1 day)  
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;3600&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ; retry (1 hour)  
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;604800&nbsp;&nbsp;&nbsp;&nbsp; ; expire (1 week)  
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;10800&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ; minimum (3 hours)  
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;)  
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;NS&nbsp;&nbsp; &nbsp;dev.  
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;A&nbsp;&nbsp; &nbsp;127.0.0.1  
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;AAAA&nbsp;&nbsp; &nbsp;::1  
\*&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;IN&nbsp;&nbsp; &nbsp;A&nbsp;&nbsp; &nbsp;127.0.0.1[/code]

Последняя строчка нам нужна для того, чтобы все домены, для которых не прописан адрес резолвились на локалхост.

Все. Нам осталось перезапустить.

[code lang="shell"]$ sudo service named restart[/code]

Проверяем

[code lang="shell"]$ nslookup test.dev 127.0.0.1  
Server:&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;127.0.0.1  
Address:&nbsp;&nbsp; &nbsp;127.0.0.1#53

Name:&nbsp;&nbsp; &nbsp;test.dev  
Address: 127.0.0.1[/code]

&nbsp;

&nbsp;

Кдасс. А как нам связывать домен с адресом?

Для этого нам нужен скрипт.

[code lang="shell"]#!/bin/bash  
TTL=86400  
RECORD=$1  
IP=$2  
(  
&nbsp;echo "server dev."  
&nbsp;echo "zone dev"

&nbsp;echo "update delete ${RECORD} A"  
&nbsp;echo "update add ${RECORD} ${TTL} A ${IP}"  
&nbsp;echo "send"  
) | /usr/bin/nsupdate[/code]

&nbsp;

Пробуем

[code lang="shell"]$ ./named.sh test.dev 1.1.1.1[/code][code lang="shell"]$ nslookup test.dev 127.0.0.1  
Server: 127.0.0.1  
Address: 127.0.0.1#53

Name: test.dev  
Address: 1.1.1.1[/code]

Возможные проблемы:

- неправильно установлены права на папку /var/named
- неправильно указан адрес с которого можно обновлять зону
- запрет в selinux - решается выполнением [code lang="shell"]$ sudo setsebool -P named\_write\_master\_zones 1[/code]

Теперь можно настроить окружение так, чтобы при запуске виртуалки ее адрес обновлялся в файле зоны через скрипт в автоматическом режиме.

