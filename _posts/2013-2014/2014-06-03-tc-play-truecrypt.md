---
layout: post
title: tc-play. Небольшая памятка про криптоконтейнеры
type: post
categories:
- HowTo
tags:
- linux
permalink: "/2014/06/03/tc-play-%d0%bd%d0%b5%d0%b1%d0%be%d0%bb%d1%8c%d1%88%d0%b0%d1%8f-%d0%bf%d0%b0%d0%bc%d1%8f%d1%82%d0%ba%d0%b0/"
---
Вокруг truecrypt какая-то нездоровая [шумиха](http://habrahabr.ru/post/224491/ "Сайт TrueCrypt сообщает о закрытии проекта и предлагает переходить на BitLocker"). Кто-то даже на трояны намекает в версии 7.1а. Так что можно попробовать свободные форки TC. Например [tc-play](https://github.com/bwalex/tc-play "Free and simple TrueCrypt Implementation based on dm-crypt").

Набросал себе памятку по мануалу (вы не подумайте, я их не не читаю :)).

```shell
$ sudo losetup /dev/loop1 <path to file> # делаем лупбек на файл с контейнером
$ sudo tcplay -m tc0 -d /dev/loop1 -e # делаем криптоустройство внешнего контейнера и заодно защищам скрытый том (если есть) от перезатирания
$ sudo tcplay -m tc1 -d /dev/loop1 # маппим скрытый контейнер (передаем пасс скрытого устройства
```

Дальше остается только примонтировать появившиеся /dev/tc* куда надо. А за остальным в мануал.

