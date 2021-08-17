---
layout: post
title: Включение TRIM на SSD с LVM/LUKS
date: 2014-06-11 12:14:58.000000000 +04:00
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
  _edit_last: '13696577'
  geo_public: '0'
  _publicize_pending: '1'
  _oembed_421f5301845b32046e052126039dce58: "{{unknown}}"
  _oembed_e7492c78fd0c56351446f4cf5a23c449: "{{unknown}}"
  _oembed_06aeba19006d5ecee51e36232307bbd6: "{{unknown}}"
  _oembed_3e82662d41b1f1c017f73498ad8c0cbe: "{{unknown}}"
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2014/06/11/%d0%b2%d0%ba%d0%bb%d1%8e%d1%87%d0%b5%d0%bd%d0%b8%d0%b5-trim-%d0%bd%d0%b0-ssd-%d1%81-lvm-luks/"
---
[Trim](https://en.wikipedia.org/wiki/Trim_%28computing%29 "TRIM") - это полезная ata-команда, которая препятствует деградации производительности SSD-дисков.

Но часто случается, что дистрибутивы не включают ее на разделах.

Первым делом надо ее включить на нативных разделах просто добавив опцию discard к записи в fstab.

```
UUID=397b890a-c661-47f4-bd2a-2260379f8c6f /boot&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ext4&nbsp;&nbsp;&nbsp; defaults,discard&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1 2
```

Как поступать с разделами, которые расположены на шифрованных томах или lvm?

Для lvm надо сначала разрешить проброс команды trim к дискам (он запрещен по дефолту).

Правим /etc/lvm/lvm.conf и меняем опцию issue_discards с 0 на 1.

```
issue_discards = 1
```

Проверяем

```
$ sudo fstrim -v /home /home: 54,4 GiB (58440941568 bytes) trimmed
```

Отлично. Но что если разделы расположены на шифрованном томе, который размещен в lvm?

**Важно: включение trim для зашифрованных томов может ослабить безопасность шифрования! Так как по перемещенным блокам можно сделать вывод о том, какая файловая система используется.**

В /etc/crypttab нужно добавить опцию allow-discard.

```
luks-xxx UUID=some-uuid none allow-discards
```

для debian-based дистрибутивов строчка немного меняется

```
luks-xxx UUID=some-uuid none luks,discard
```

Теперь надо пересобрать initramfs.

Для rpm-based

```
$ sudo dracut --force
```

У fedora 18 есть баг из-за которого нужно указывать пусть к crypttab

```
$ sudo dracut --force -I /etc/crypttab
```

Проверим, что ctypttab был успешно добавлен в initrd.

```
$ sudo lsinitrd |grep crypttab
```

Теперь нужно заставить систему отправлять trim для томов.

```
# echo -e "fstrim /\nfstrim /home\nfstrim /boot" > /etc/cron.hourly/fstrim
```

&nbsp;

