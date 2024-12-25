---
layout: post
title: Настройка торрент-клиента deluge на удаленном сервере
type: post
categories:
- HowTo
tags:
- linux
permalink: "/2015/08/02/%d0%bd%d0%b0%d1%81%d1%82%d1%80%d0%be%d0%b9%d0%ba%d0%b0-%d1%82%d0%be%d1%80%d1%80%d0%b5%d0%bd%d1%82-%d0%ba%d0%bb%d0%b8%d0%b5%d0%bd%d1%82%d0%b0-deluge-%d0%bd%d0%b0-%d1%83%d0%b4%d0%b0%d0%bb%d0%b5%d0%bd/"
---
[![]({{ site.baseurl }}/assets/images/2015/08/deluge-bittorrent-client.jpg)]({{ site.baseurl }}/2015/08/deluge-bittorrent-client.jpg)Все последующие шаги описываются на примере Fedora, но могут быть адаптированы под любой другой дистрибутив.

## Установка

Установка - это самое простое, что может быть.  
```shell
$ sudo dnf install deluge-daemon deluge-console
```  
Ставим консольный клиент, а так же cli для него.

Пока все. Клиент готов к работе. Его уже можно включить и пользоваться.

```shell
$ sudo systemctl enable deluge-daemon  
$ sudo systemctl start deluge-daemon
```

Но в такой конфигурации есть много проблем:

- отсутствие логов
- неправильное распределение по портам сервера

Вам это надо? :)

## Логи

Сразу после установки демон готов к запуску. Но та конфигурация, которую предлагают поставщики дистрибутива - она не совсем удачна. В ней отсутсвует логирование происходящего.

Для этого нам надо поставить logrotate.  
```
$ sudo dnf install logrotate
```

Сконфигурировать его для поддержки новых правил ротации. Для этого создадим файл **/etc/logrotate.d/deluge** примерно следующего содержания

```
/var/log/deluge/*.log {  
 rotate 4  
 weekly  
 missingok  
 notifempty  
 compress  
 delaycompress  
 sharedscripts  
 postrotate  
 initctl restart deluged >/dev/null 2>&1 || true  
 initctl restart deluge-web >/dev/null 2>&1 || true  
 endscript  
}
```

А так же папку для хранения логов. И дадим ей нужные права.

```
$ sudo mkdir /var/log/deluge/  
$ sudo chown deluge:deluge /var/log/deluge
```

Теперь осталось включить поддержку логов для демона.

Создаем новое описание демона для systemd в /etc/systemd/system/deluged.service

```
[Unit]  
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
WantedBy=multi-user.target
```

Отлично. Осталось настроить iptables и сам deluge.

## Настройка iptables

В ряде случаем достаточно просто открыть нужные порты  
```
$ sudo iptables -A INPUT -p tcp --dport 56881:56889 -j ACCEPT  
$ sudo iptables -A INPUT -p udp --dport 56881:56889 -j ACCEPT
```

Но в некоторых конфигурациях могут [наблюдаться](http://www.linuxquestions.org/questions/showthread.php?p=5145026) проблемы с механизмом conntrack, который помечает ряд пакетов как invalid (особенно это касается dht трафика).

Поэтому стоит отключить conntrack для всех соединения deluge.

```
$ sudo iptables -t raw -I PREROUTING -p udp --dport 56881:57200 -j NOTRACK  
$ sudo iptables -t raw -I OUTPUT -p udp --sport 56881:57200 -j NOTRACK  
$ sudo iptables -t raw -I PREROUTING -p tcp --dport 56881:57200 -j NOTRACK  
$ sudo iptables -t raw -I OUTPUT -p tcp --sport 56881:57200 -j NOTRACK  
$ sudo iptables -I INPUT -p icmp --icmp-type 3 -j ACCEPT  
$ sudo iptables -I INPUT -p icmp --icmp-type 4 -j ACCEPT  
$ sudo iptables -I INPUT -p icmp --icmp-type 11 -j ACCEPT  
$ sudo iptables -I INPUT -p icmp --icmp-type 12 -j ACCEPT
```

В любом случае тепень надо сохранить конфигарцию iptables.

```
$ sudo /usr/libexec/iptables/iptables.init save
```

## Локальная авторизация

Чтобы мы могли успешно пользоваться deluge-console локальная авторизация должна быть включена для нашего пользователя.

Т.е. должен быть файл ~/.config/deluge/auth содержащий строку логина-пароля

```
localclient:тут_длинный_хеш:10
```

Скопировать этот файл можно из каталога /var/lib/deluge/.config/deluge

```
$ sudo cat /var/lib/deluge/.config/deluge/auth >> ~/.config/deluge/auth
```

## Запуск и гонфигурирование демона

```
$ sudo systemctl enable deluged  
$ sudo systemctl start deluged
```

Тем самым мы запустили демона, конфиг которого был описан ранее.

Осталось его настроить.

```
$ deluge-console
```

Вбиваем следующий набор команд

Заставляем deluge слушать только определенный диапазон портов (который был открыт ранее)  
```
config -s listen_ports (56881, 56891)
```  
Принудительно заставляем демон выходить только через определенный набор портов  
```
config -s outgoing_ports (56890, 57200)
```

## Все

Теперь все. Каталог, в который будут сохраняться торренты - /var/lib/deluge/Downloads

Дополнительные ссылки:  
[https://wiki.archlinux.org/index.php/Deluge](https://wiki.archlinux.org/index.php/Deluge)

