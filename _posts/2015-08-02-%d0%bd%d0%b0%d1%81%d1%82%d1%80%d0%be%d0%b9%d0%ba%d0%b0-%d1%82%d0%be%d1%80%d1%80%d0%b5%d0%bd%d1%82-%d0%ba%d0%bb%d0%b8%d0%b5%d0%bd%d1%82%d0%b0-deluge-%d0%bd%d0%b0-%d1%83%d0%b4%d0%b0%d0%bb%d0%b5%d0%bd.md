---
layout: post
title: Настройка торрент-клиента deluge на удаленном сервере
date: 2015-08-02 20:13:12.000000000 +03:00
type: post
parent_id: '0'
published: true
password: ''
status: publish
categories:
- HowTo
tags:
- linux
meta:
  _wpcom_is_markdown: '1'
  sharing_disabled: '1'
  _rest_api_published: '1'
  _rest_api_client_id: "-1"
  _publicize_job_id: '13336590486'
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2015/08/02/%d0%bd%d0%b0%d1%81%d1%82%d1%80%d0%be%d0%b9%d0%ba%d0%b0-%d1%82%d0%be%d1%80%d1%80%d0%b5%d0%bd%d1%82-%d0%ba%d0%bb%d0%b8%d0%b5%d0%bd%d1%82%d0%b0-deluge-%d0%bd%d0%b0-%d1%83%d0%b4%d0%b0%d0%bb%d0%b5%d0%bd/"
---
[![]({{ site.baseurl }}/assets/images/2015/08/deluge-bittorrent-client.jpg?w=150)](https://russianpenguin.files.wordpress.com/2015/08/deluge-bittorrent-client.jpg)Все последующие шаги описываются на примере Fedora, но могут быть адаптированы под любой другой дистрибутив.

## Установка

Установка - это самое простое, что может быть.  
[code lang="shell"]$ sudo dnf install deluge-daemon deluge-console[/code]  
Ставим консольный клиент, а так же cli для него.

Пока все. Клиент готов к работе. Его уже можно включить и пользоваться.

[code lang="shell"]$ sudo systemctl enable deluge-daemon  
$ sudo systemctl start deluge-daemon[/code]

Но в такой конфигурации есть много проблем:

- отсутствие логов
- неправильное распределение по портам сервера

Вам это надо? :)

## Логи

Сразу после установки демон готов к запуску. Но та конфигурация, которую предлагают поставщики дистрибутива - она не совсем удачна. В ней отсутсвует логирование происходящего.

Для этого нам надо поставить logrotate.  
[code]$ sudo dnf install logrotate[/code]

Сконфигурировать его для поддержки новых правил ротации. Для этого создадим файл **/etc/logrotate.d/deluge** примерно следующего содержания

[code]/var/log/deluge/\*.log {  
 rotate 4  
 weekly  
 missingok  
 notifempty  
 compress  
 delaycompress  
 sharedscripts  
 postrotate  
 initctl restart deluged \>/dev/null 2\>&1 || true  
 initctl restart deluge-web \>/dev/null 2\>&1 || true  
 endscript  
}[/code]

А так же папку для хранения логов. И дадим ей нужные права.

[code]$ sudo mkdir /var/log/deluge/  
$ sudo chown deluge:deluge /var/log/deluge[/code]

Теперь осталось включить поддержку логов для демона.

Создаем новое описание демона для systemd в /etc/systemd/system/deluged.service

[code][Unit]  
Description=Deluge Bittorrent Client Daemon  
After=network.target

[Service]  
Type=simple  
User=deluge  
Group=deluge  
UMask=007

ExecStart=/usr/bin/deluged -d -l /var/log/deluge/daemon.log -L warning

Restart=always  
TimeoutStopSec=300

[Install]  
WantedBy=multi-user.target[/code]

Отлично. Осталось настроить iptables и сам deluge.

## Настройка iptables

В ряде случаем достаточно просто открыть нужные порты  
[code]$ sudo iptables -A INPUT -p tcp --dport 56881:56889 -j ACCEPT  
$ sudo iptables -A INPUT -p udp --dport 56881:56889 -j ACCEPT[/code]

Но в некоторых конфигурациях могут [наблюдаться](http://www.linuxquestions.org/questions/showthread.php?p=5145026) проблемы с механизмом conntrack, который помечает ряд пакетов как invalid (особенно это касается dht трафика).

Поэтому стоит отключить conntrack для всех соединения deluge.

[code]$ sudo iptables -t raw -I PREROUTING -p udp --dport 56881:57200 -j NOTRACK  
$ sudo iptables -t raw -I OUTPUT -p udp --sport 56881:57200 -j NOTRACK  
$ sudo iptables -t raw -I PREROUTING -p tcp --dport 56881:57200 -j NOTRACK  
$ sudo iptables -t raw -I OUTPUT -p tcp --sport 56881:57200 -j NOTRACK  
$ sudo iptables -I INPUT -p icmp --icmp-type 3 -j ACCEPT  
$ sudo iptables -I INPUT -p icmp --icmp-type 4 -j ACCEPT  
$ sudo iptables -I INPUT -p icmp --icmp-type 11 -j ACCEPT  
$ sudo iptables -I INPUT -p icmp --icmp-type 12 -j ACCEPT[/code]

В любом случае тепень надо сохранить конфигарцию iptables.

[code]$ sudo /usr/libexec/iptables/iptables.init save[/code]

## Локальная авторизация

Чтобы мы могли успешно пользоваться deluge-console локальная авторизация должна быть включена для нашего пользователя.

Т.е. должен быть файл ~/.config/deluge/auth содержащий строку логина-пароля

[code]localclient:тут\_длинный\_хеш:10[/code]

Скопировать этот файл можно из каталога /var/lib/deluge/.config/deluge

[code]$ sudo cat /var/lib/deluge/.config/deluge/auth \>\> ~/.config/deluge/auth[/code]

## Запуск и гонфигурирование демона

[code]$ sudo systemctl enable deluged  
$ sudo systemctl start deluged[/code]

Тем самым мы запустили демона, конфиг которого был описан ранее.

Осталось его настроить.

[code]$ deluge-console[/code]

Вбиваем следующий набор команд

Заставляем deluge слушать только определенный диапазон портов (который был открыт ранее)  
[code]config -s listen\_ports (56881, 56891)[/code]  
Принудительно заставляем демон выходить только через определенный набор портов  
[code]config -s outgoing\_ports (56890, 57200)[/code]

## Все

Теперь все. Каталог, в который будут сохраняться торренты - /var/lib/deluge/Downloads

Дополнительные ссылки:  
[https://wiki.archlinux.org/index.php/Deluge](https://wiki.archlinux.org/index.php/Deluge)

