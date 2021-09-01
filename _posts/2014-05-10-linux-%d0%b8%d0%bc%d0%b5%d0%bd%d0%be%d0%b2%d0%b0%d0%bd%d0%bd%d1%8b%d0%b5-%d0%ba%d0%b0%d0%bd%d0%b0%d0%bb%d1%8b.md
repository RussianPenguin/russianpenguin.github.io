---
layout: post
title: 'Linux: именованные каналы'
date: 2014-05-10 21:22:07.000000000 +04:00
type: post
categories:
- HowTo
tags:
- linux
permalink: "/2014/05/10/linux-%d0%b8%d0%bc%d0%b5%d0%bd%d0%be%d0%b2%d0%b0%d0%bd%d0%bd%d1%8b%d0%b5-%d0%ba%d0%b0%d0%bd%d0%b0%d0%bb%d1%8b/"
---
Для взаимодействия между различными процессами в *nix можно создавать именованные каналы, которые позволяют перенапрявлять ввод/вывод.

В одном терминале

```
$ mkfifo named_pipe $ gzip -9 -c < named_pipe > out.gz &
```

В другом

```
echo Hello, world! > named_pipe
```

Теперь мы можем увидеть полученный файл.

```
$ zcat out.gz Hello, world!
```

![Пример использования именованных каналов в linux]({{ site.baseurl }}/assets/images/2014/05/073.png)

Пример использования именованных каналов в linux

И удалить именованный канал

```
$ rm named_pipe
```
