---
layout: post
title: 'Openkinect+linux: поддержка звука'
date: 2014-08-25 21:32:45.000000000 +04:00
type: post
categories:
- HowTo
- kinect
- linux
tags: []
permalink: "/2014/08/25/openkinectlinux-%d0%bf%d0%be%d0%b4%d0%b4%d0%b5%d1%80%d0%b6%d0%ba%d0%b0-%d0%b7%d0%b2%d1%83%d0%ba%d0%b0/"
---
Есть такая демка во openkinect - micview. Для её использования нужна прошивка из состава kinect sdk или та, которая идет с Xbox360.

Подробнее можно глянуть [официальный мануал](http://openkinect.org/wiki/Protocol_Documentation "Openkinect: Protocol Documentation").

А тут немного о том, где же эту прошивку взять (вики шлет в разные списки рассылки или гугл).

Если мы в linux, то нам нужен audios.bin из состава обновления xbox360. В windows нам нужен тот, который идет в составе kinect sdk (из-за ограничения стека usb в винде прошивка от xbox360 нормально работает только в linux).

- скачиваем [обновление](http://download.microsoft.com/download/4/1/D/41D9A2BA-3B48-4BD5-B613-122E7C3A1390/SystemUpdate12611.zip "Обновление для xbox360 с audios.bin")
- скачиваем скрипт [extract360.py](https://github.com/rene0/xbox360/blob/master/extract360.py "extract360.py") для распаковки ресурсов xbox
- вытаскиваем из скачанного архива файл _FFFE07DF00000001_
- натравливаем
```shell
$ extract360.py FFFE07DF00000001
```
- кладем _audios.bin_ в _~/.libfreenect_


[!Openkinect demo: micview]({{ site.baseurl }}/assets/images/2014/08/d0b2d18bd0b4d0b5d0bbd0b5d0bdd0b8d0b5_056.png)

