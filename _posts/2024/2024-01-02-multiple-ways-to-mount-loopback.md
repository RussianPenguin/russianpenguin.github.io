---
layout: post
title: 'Linux: несколько способов монтирования образов'
type: post
status: publish
categories:
- HowTo
tags:
- shell
- linux
- gnome
permalink: "/2024/01/02/mutiple-ways-to-mount-loopback-device"
---

<img class="kdpv" src="{{ site.baseurl }}/assets/images/2024/multiple-ways-to-mount-loopback.png" alt="Монтирование образа с несколькими разделами" title="Монтирование образа с несколькими разделами" />

### Образ с одним разделом

образ с одним разделом или нужно прьсто посмотреть структура разделов не монтируя.

Активировать

```shell
# losetup /dev/loop0 some.img
```

Деактивировать


```shell
# losetup -d /dev/loop0
```
### Образ с несколькими разделами

Если образ содержит несколько разделов и нужну примонтирвоать какой-либо из них.

Активировать

```shell
# kpartx -v -a some.img
add map loop0p1 (251:0): 0 497664 linear /dev/loop0 2048
add map loop0p2 (251:1): 0 66605058 linear /dev/loop0 501758
add map loop0p5 (251:2): 0 66605056 251:1 2
# ls /dev/mapper/
control  loop0p1  loop0p2  loop0p5
# mount /dev/mapper/loop0p1 /mnt/test
# mount  | grep test
/dev/mapper/loop0p1 on /mnt/test type ext2 (rw)
```

Деактивировать

```shell
# kpartx -v -d logging-test.img
del devmap : loop0p2
del devmap : loop0p1
loop deleted : /dev/loop0
#
```

