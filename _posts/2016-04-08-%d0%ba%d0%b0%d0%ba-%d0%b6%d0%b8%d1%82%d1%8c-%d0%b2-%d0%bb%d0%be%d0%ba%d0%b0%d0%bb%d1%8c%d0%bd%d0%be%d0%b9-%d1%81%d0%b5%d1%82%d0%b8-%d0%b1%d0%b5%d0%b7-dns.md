---
layout: post
title: Как жить в локальной сети без dns для локальных ресурсов
date: 2016-04-08 00:24:37.000000000 +03:00
type: post
parent_id: '0'
published: true
password: ''
status: publish
categories:
- HowTo
tags:
- dns
- linux
- сеть
meta:
  _wpcom_is_markdown: '1'
  _rest_api_published: '1'
  _rest_api_client_id: "-1"
  _publicize_job_id: '21561748915'
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2016/04/08/%d0%ba%d0%b0%d0%ba-%d0%b6%d0%b8%d1%82%d1%8c-%d0%b2-%d0%bb%d0%be%d0%ba%d0%b0%d0%bb%d1%8c%d0%bd%d0%be%d0%b9-%d1%81%d0%b5%d1%82%d0%b8-%d0%b1%d0%b5%d0%b7-dns/"
---
<p><img class="alignleft size-thumbnail wp-image-1354" src="{{ site.baseurl }}/assets/images/2016/04/extra-bonjour.png?w=150" alt="Extra-Bonjour" width="150" height="150" />Или сказ о том, как перестать бояться и начать раздавать динамические адреса в локальной сети.</p>
<p>Задача:</p>
<ul>
<li>необходимо автоматизировать распределение имен различным устройствам в сети.</li>
</ul>
<p>Проблемы</p>
<ul>
<li>доисторический (ископаемый) роутер, который не умеет dd-wrt/openwrt и иже. А вместе с этим он не умеет статические адреса или локальный dns.</li>
<li>много iot-желаза в локальной сети к которому хочется получать доступ по имени (доменному конечно же).</li>
<li>Очень много железа, которое появляется в сети лишь на короткое время, а доступ к нему по сети нужен (ну не прописывать же ему постоянно статику?)</li>
<li>большое количество скриптов автоматизации, которым надо откуда-то брать именя устройств.</li>
</ul>
<p>Проблему можно решить несколькими путями:</p>
<ul>
<li>Поставить слабую железку, поставить на нее bind, поднять локальную доменную зону и убрать с роутера роль dhcp и dns-сервера. Минус в том, что слабой железки может и не быть.</li>
<li>Поменять роутер на менее доисторический. Минус в том, что роутера может не быть под рукой.</li>
<li>Воспользоваться протоколом <a href="https://en.wikipedia.org/wiki/Zero-configuration_networking">zeroconf</a>. Минусы тоже есть - возможный конфликт имен устройств.</li>
</ul>
<p>Если с первыми двума вариантами все более-менее понятно, то на третьем стоит остановиться подробно. Так как он решает проблему наименее затратным способом.</p>
<p>Протокол описывает:</p>
<ul>
<li>назначение адресов устройствам в сети (диапазон 169.254.*)</li>
<li>разрешение имен</li>
<li>обнаружение сервисов</li>
</ul>
<p>Поскольку адреса у устройств уже есть (dhcp же), то нас будет интересовать только та часть протокола, где рассказывается про обнаружение сервисов и разрешение имен. Это mDNS+DNSSD.</p>
<p>В nix\bsd за эту часть протокола отвечает сервис avahi</p>
<p>В ряде дистрибутивов он включен и нормально настроен сразу.</p>
<p>На примере федоры посмотрим как его поставить и настроить.</p>
<p>[code lang="shell"]$ sudo dnf install avahi-daemon avahi-utils<br />
$ sudo systemctl enable avahi-daemon<br />
$ sudo systemctl start avahi-daemon[/code]</p>
<p>Если у вас в сети уже есть устройства, где активирован avahi, то можно посмотреть на то, найдет ли оно какие-либо устройства</p>
<p>[code lang="shell"]$ avahi-browse -alr<br />
+   eth0 IPv4 workstation                                   Remote Disk Management local<br />
=   eth0 IPv4 workstation                                   Remote Disk Management local<br />
   hostname = [workstation.local]<br />
   address = [192.168.1.10]<br />
   port = [22]<br />
   txt = [][/code]</p>
<p>Как видим что-то нашло.</p>
<p>Мы можем попробовать его попинговать.</p>
<p>[code lang="shell"]$ ping workstation.local<br />
ping: unknown host workstation.local[/code]</p>
<p>Если вы увидели такую картину, то это означает лишь одно - mdns для получения имен доменов у вас не подключен.</p>
<p>Чтобы его включить требуется отредактировать /etc/nsswitch.conf.</p>
<p>В строчку hosts нужно добавить mdns_minimal [NOTFOUND=return] перед dns.</p>
<p>[code]hosts: files mdns_minimal [NOTFOUND=return] dns myhostname mymachines[/code]</p>
<p>После перезагрузки или перезапуска соотвествующего сервиса пингуем снова.</p>
<p>[code lang="shell"]$ ping -c 4 workstation.local<br />
PING workstation.local (192.168.1.10) 56(84) bytes of data.<br />
64 bytes from workstation.local (192.168.1.10): icmp_req=1 ttl=64 time=0.449 ms<br />
64 bytes from workstation.local (192.168.1.10): icmp_req=2 ttl=64 time=0.469 ms<br />
64 bytes from workstation.local (192.168.1.10): icmp_req=3 ttl=64 time=0.467 ms<br />
64 bytes from workstation.local (192.168.1.10): icmp_req=4 ttl=64 time=0.393 ms</p>
<p>--- workstation.local ping statistics ---
  
4 packets transmitted, 4 received, 0% packet loss, time 3004ms  
rtt min/avg/max/mdev = 0.393/0.444/0.469/0.037 ms[/code]

Если у вас очень медленно резолвятся локальные домены, то стоит попробовать использовать модуль mdns4\_minimal.

Задача раздачи локальных имен полностью решена. В данном случае я не затрагиваю dnssd поскольку цель была лишь обеспечить доступность хостов по имени.

При желании поднять zeroconf можно как на ардуине, так и на модулях esp8266.

Вы можете столнуться с проблемами из-за того, что некоторые продукты используют зону local для своих целей.

Например торренты часто используют [retracker.local](https://ru.wikipedia.org/wiki/%D0%A0%D0%B5%D1%82%D1%80%D0%B5%D0%BA%D0%B5%D1%80) для обозначения внутрисетевого трекера.

