---
layout: post
title: 'VirtualBox: получение адреса гостевой машины'
date: 2018-02-24 17:15:10.000000000 +03:00
type: post
categories:
- HowTo
tags:
- dhcp
- virtualbox
permalink: "/2018/02/24/virtualbox-%d0%bf%d0%be%d0%bb%d1%83%d1%87%d0%b5%d0%bd%d0%b8%d0%b5-%d0%b0%d0%b4%d1%80%d0%b5%d1%81%d0%b0-%d0%b3%d0%be%d1%81%d1%82%d0%b5%d0%b2%d0%be%d0%b9-%d0%bc%d0%b0%d1%88%d0%b8%d0%bd%d1%8b/"
---
```
vboxmanage guestproperty get <machine name > "/VirtualBox/GuestInfo/Net/<network id>/V4/IP
```

Например получить адрес в публичной сети, который был роздан при помощи встроенного dhcp.

```
vboxmanage guestproperty get machine "/VirtualBox/GuestInfo/Net/1/V4/IP"
```

Подробности:&nbsp;[https://www.virtualbox.org/manual/ch08.html](https://www.virtualbox.org/manual/ch08.html)

