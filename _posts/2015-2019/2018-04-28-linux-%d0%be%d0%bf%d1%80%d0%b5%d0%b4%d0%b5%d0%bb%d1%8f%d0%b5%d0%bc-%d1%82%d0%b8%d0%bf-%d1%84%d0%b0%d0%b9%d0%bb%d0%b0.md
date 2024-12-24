---
layout: post
title: 'Linux: определяем тип файла'
type: post
categories: []
tags:
- bash
- linux
- office
permalink: "/2018/04/28/linux-%d0%be%d0%bf%d1%80%d0%b5%d0%b4%d0%b5%d0%bb%d1%8f%d0%b5%d0%bc-%d1%82%d0%b8%d0%bf-%d1%84%d0%b0%d0%b9%d0%bb%d0%b0/"
excerpt: Иногда к нам приходят файлы о которых мы ничего не знаем. Как определить
  тип файла в linux?
---
![2018-04-28-22:37:39_718x635]({{ site.baseurl }}/assets/images/2018/04/2018-04-28-223739_718x635.png){:.img-fluid}

В попытках открыть csv-файл от одной организации я увидел картину из скриншота. В файле должны были быть табличные данные, а оказалась какая-то белиберда с отсылкой к VBA. Очевидно, что там что-то из msоffice, но как узнать это точно?

Для определения типа файла по его сигнатуре в unix-подобных операционных системах существует утилита [file](ftp://ftp.astron.com/pub/file) (```man 1 file```).

```shell
 $ file test.csv  
test.csv: Composite Document File V2 Document, Little Endian, Os: Windows, Version 5.2, Code page: 1251, Author: , Last Saved By: , Name of Creating Application: Microsoft Excel, Create Time/Date: Sat Apr 28 10:33:01 2018, Last Saved Time/Date: Sat Apr 28 10:45:37 2018, Security: 0
```

Видно, что тип файла Composite Document File V2 Document. Но какой именно это формат? У экселя их очень много. Есть отличная утилита [unoconv](https://debianworld.ru/articles/unoconv-konvertaciya-word-pdf-swf-html-ppt-dokumentov-v-debian-ubuntu/) из поставки openoffice/libreoffice.

```shell
 $ sudo dnf install unoconv
```

```shell
 $ mv test.csv test  
$ unoconv --format=ods test
```

Сразу возникает вопрос: зачем мы убрали у файла расширение? Это особенность утилиты: она использует расширение файла для определения типа, а если расширения нет, то опирается на метаинформацию. Поэтому нам потребовалось убрать расширение.

Теперь все хорошо и нормально открывается.

