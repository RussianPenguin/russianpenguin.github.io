---
layout: post
title: 'Mikrotik: multicast в wireless-сетях'
date: 2019-04-28 18:33:54.000000000 +03:00
type: post
categories:
- HowTo
- linux
tags:
- avahi
- mikrotik
- сеть
permalink: "/2019/04/28/mikrotik-multicast-%d0%b2-wireless-%d1%81%d0%b5%d1%82%d1%8f%d1%85/"
excerpt: Если у вас роутер mikrotik и вы хотите использовать udp-multicast в беспроводной
  сети, то нужно выполнить  дополнительную настройку интерфейса.
---
![Mikrotik-RB951G-2HnD]({{ site.baseurl }}/assets/images/2019/04/mikrotik-rb951g-2hnd.png){:.img-fluid}

Кратко: если у вас роутер mikrotik и вы хотите использовать udp-multicast в беспроводной сети, то надо включить опцию multicast helper в настройках интерфейса. Если этого не сделать, то пакеты будут теряться. А дальше мы посмотрим как можно диагностировать подобную ситуацию.

Обновил я свой роутер до mikrotik (оказалась очень удобная штука). В моей локальной сети для всяких разных iot был настроен zeroconfig [по собственному мануалу.]({{ site.baseurl }}/2016/04/08/%d0%ba%d0%b0%d0%ba-%d0%b6%d0%b8%d1%82%d1%8c-%d0%b2-%d0%bb%d0%be%d0%ba%d0%b0%d0%bb%d1%8c%d0%bd%d0%be%d0%b9-%d1%81%d0%b5%d1%82%d0%b8-%d0%b1%d0%b5%d0%b7-dns/) И тут что-то пошло не так.

Выражалось это все тем, что мультикаст с запросом адреса есть, а мультикаст-ответа нет. При этом машина, которой этот запрос предназначался, отвечала. Или ответ приходил, но спустя пол минуты или больше.

Мы можем эту итуацию наблюдать на скриншоте wireshark ниже.

![wireshark.png]({{ site.baseurl }}/assets/images/2019/04/wireshark.png){:.img-fluid}

Рассмотрим на примере хоста diskstation.local.

На нем я смотрел в tcpdump и видел, что ответ есть.

```shell
# tcpdump port mdns  
...  
18:04:29.405838 IP6 fe80::e3de:bf6f:105e:ad4d.mdns > ff02::fb.mdns: 0 [2q] A (QM)? diskstation.local. AAAA (QM)? diskstation.local. (41)  
18:04:29.405885 IP 192.168.88.252.mdns > 224.0.0.251.mdns: 0 [2q] A (QM)? diskstation.local. AAAA (QM)? diskstation.local. (41)  
18:04:29.406152 IP6 fe80::211:32ff:fe80:3eb5.mdns > ff02::fb.mdns: 0*- [0q] 1/0/0 (Cache flush) AAAA fe80::211:32ff:fe80:3eb5 (57)  
18:04:29.406388 IP diskstation.lan.mdns > 224.0.0.251.mdns: 0*- [0q] 2/0/0 (Cache flush) AAAA fe80::211:32ff:fe80:3eb5, (Cache flush) A 192.168.88.250 (73)
```

Интернеты не смотгли дать вразумительного ответа почему так происходит. Была лишь одна зацепка - это разбор проблем с вещанием iptv. И именно там я встретил заметку о том, что гоже было бы включить multicast helper в настройках интерфейса wifi (предварительно нажать advanced settings).

![wifi-config.png]({{ site.baseurl }}/assets/images/2019/04/wifi-config.png){:.img-fluid}

