---
layout: post
title: 'Dnf: смотрим содержимое пакетов'
type: post
status: publish
categories:
- HowTo
tags:
- shell
- linux
- fedora
- dnf
permalink: "/2022/03/07/dnf_смотрим_содержимое_пакета"
---

Иногда требуется посмотреть содержимое пакета. Для этого есть пара удобный опций dnf и rpm.

У dnf это команда ```repoquery``` с опцией ```-l```. У ```rpm``` это запрос ```-q``` c опцией ```-l```.

**Важно**. Rpm работает только с установленными пакетами, а через dnf можно посмотреть состав любого пакета в репозитарии.

Посмотрим содержимое пакета fbreader.

```shell
# dnf repoquery -l fbreader
Последняя проверка окончания срока действия метаданных: 0:00:49 назад, Пн 07 мар 2022 17:16:45.
/usr/bin/FBReader
/usr/lib/.build-id
/usr/lib/.build-id/25
/usr/lib/.build-id/25/6e45979449fce7ee768d016a2cb5bc90d3afc6
/usr/share/FBReader
...
```

```shell
# rpm -ql fbreader
/usr/bin/FBReader
/usr/lib/.build-id
/usr/lib/.build-id/25
/usr/lib/.build-id/25/6e45979449fce7ee768d016a2cb5bc90d3afc6
/usr/share/FBReader
...
```

**Литература**
* [DNF Command Reference](https://dnf.readthedocs.io/en/latest/command_ref.html)