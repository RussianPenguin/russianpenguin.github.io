---
layout: post
title: 'OpenWRT: блокировка рекламы'
date: 2016-08-26 00:14:12.000000000 +03:00
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
  _oembed_b086958113836c4c534ce43d44538156: "{{unknown}}"
  _rest_api_published: '1'
  _rest_api_client_id: "-1"
  _publicize_job_id: '26150305742'
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2016/08/26/openwrt-%d0%b1%d0%bb%d0%be%d0%ba%d0%b8%d1%80%d0%be%d0%b2%d0%ba%d0%b0-%d1%80%d0%b5%d0%ba%d0%bb%d0%b0%d0%bc%d1%8b/"
---
[![1280px-openwrt_logo-svg]({{ site.baseurl }}/assets/images/2016/08/1280px-openwrt_logo-svg.png?w=150)](https://openwrt.org/)Есть отличная прошивка на базе всеми любимого линупса для всякой разной техники вроде роутеров и микрокомпьютеров - это [OpenWRT](https://openwrt.org/).

А поскольку эта штука работает в качестве локального днс-сервера, то грех не научить ее блокировать на корню всякие даблклик.нет и другое непотребство.

Рассматривать будем ситуацию при которой у нас установлена дефолтная конфигурация c dnsmasq.

Сначала нам потребуется поставить пакет dnsmasq-full взамен стандартного (он чуть больше по объему и тянет больше зависимостей, и предоставляет больше возможностей по настройке).

[code]# opkg update  
# opkg remove dnsmasq  
# opkg install dnsmasq-full  
# /etc/init.d/dnsmasq restart[/code]

Посмотрим на файл /tmp/etc/dnsmasq.conf. Нас будут интересовать следующие два параметра.

[code]addn-hosts=/tmp/hosts  
conf-dir=/tmp/dnsmasq.d[/code]

Буквально это значит, что дополнительные файлы в формате /etc/hosts хранятся в /tmp/hosts, а дополнительные конфиги - в /tmp/dnsmasq.d.

Теперь нам потребуется скрипт, который будет скачивать листы. Поместим его в /root/bin/adblock.sh

[code lang="shell"]#!/bin/sh  
echo "download adblock rules"

if [! -d /tmp/dnsmasq.d]; then  
 mkdir /tmp/dnsmasq.d  
fi

if [! -d /tmp/hosts]; then  
 mkdir /tmp/hosts  
fi

touch /tmp/dnsmasq.d/adblock.conf

wget -O /tmp/dnsmasq.d/adblock.conf "http://pgl.yoyo.org/adservers/serverlist.php?hostformat=dnsmasq&amp;showintro=0&amp;mimetype=plaintext"

echo "" \> /tmp/hosts/adblock

wget -O /tmp/adblock https://hosts-file.net/ad\_servers.txt  
sed 's/^\(.\*\).$/\1/' /tmp/adblock \>\> /tmp/hosts/adblock  
wget -O /tmp/adblock https://adaway.org/hosts.txt  
sed 's/^\(.\*\).$/\1/' /tmp/adblock \>\> /tmp/hosts/adblock  
wget -O /tmp/adblock http://winhelp2002.mvps.org/hosts.txt  
sed 's/^\(.\*\).$/\1/' /tmp/adblock \>\> /tmp/hosts/adblock

if [-f /tmp/adblock]; then  
 rm /tmp/adblock  
fi

/etc/init.d/dnsmasq enabled && /etc/init.d/dnsmasq restart[/code]

Первым мы скачиваем файл с блокировками в формате dnsmasq, а последующие запросы - это блокировки в формате hosts. Соответственно раскладываем их по разным папкам и рестартим сервис.

Не забываем поставить +x на файл.

Теперь нужно добавить этот скрипт в крон дабы он обновлялся.

[code] # crontab -e[/code]

и пишем туда что-то вроде

[code]\* \*/12 \* \* \* /bin/sh /root/bin/adblock.sh[/code]

Это означает, что раз в 12 часов списки будут обновляться.

Естественно, что надо включить крон. По дефолту он выключен.

[code]# /etc/init.d/cron enable  
# /etc/init.d/cron enabled && /etc/init.d/cron start[/code]

Теперь осталось сделать так, чтобы при поднятии сетевого интерфейса скрипт сразу же обновлял правила.

А для этого нам потребуется создать файл /etc/hotplug.d/iface/50-adblock примерно следующего вида.

[code]#!/bin/sh  
[ifup = "$ACTION" -a "$DEVICE" = eth1] && {  
 /bin/sh /root/bin/update\_adblock.sh  
}[/code]

Где eth1 - это ваш wan-интерфейс.

Все. Можно перезагрузить роутер для проверки что все настроено корректно.

Рекламные домены вроде doubleclick.de должны резолвиться либо на 0.0.0.0, либо на 127.0.0.1.

[code]$ nslookup doubleclick.de  
Server: 192.168.0.1  
Address: 192.168.0.1#53

Name: doubleclick.de  
Address: 127.0.0.1[/code]

Чтобы точно убедиться, что все работает как надо - проверяйте что сразу после поднятия wan появились файлы /tmp/dnsmasq.d/adblock.conf и /tmp/hosts/adblock.

Учтите, что в сумме размер списков блокировки составит более 100 тысяч записей. И если роутер слабоват, то придется какой-то из списков отключать.

И да - это не панацея. Это просто хороший способ обезопасить электронные читалки, телефоны и разную мелкую технику, которая ходит в инет от баннеров и прочего добра.

Источники:

- [https://wiki.openwrt.org/doc/networking/routing](https://wiki.openwrt.org/doc/networking/routing)
- [https://forum.openwrt.org/viewtopic.php?id=50407](https://forum.openwrt.org/viewtopic.php?id=50407)
- [https://wiki.openwrt.org/doc/howto/cron](https://wiki.openwrt.org/doc/howto/cron)
- [https://wiki.openwrt.org/ru/doc/howto/dhcp.dnsmasq](https://wiki.openwrt.org/ru/doc/howto/dhcp.dnsmasq)
- [https://forum.openwrt.org/viewtopic.php?id=25304](https://forum.openwrt.org/viewtopic.php?id=25304)
- [https://wiki.openwrt.org/doc/techref/initscripts](https://wiki.openwrt.org/doc/techref/initscripts)
- [https://wiki.openwrt.org/doc/techref/initscripts](https://wiki.openwrt.org/doc/techref/initscripts)
- [https://wiki.openwrt.org/ru/doc/uci/dhcp](https://wiki.openwrt.org/ru/doc/uci/dhcp)
