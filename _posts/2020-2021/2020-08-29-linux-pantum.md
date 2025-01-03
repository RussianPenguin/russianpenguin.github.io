---
layout: post
title: 'Linux: Настраиваем МФУ pantum'
type: post
categories:
- HowTo
tags:
- cups
- администрирование
- linux
- sane
permalink: "/2020/08/29/linux-%d0%bd%d0%b0%d1%81%d1%82%d1%80%d0%b0%d0%b8%d0%b2%d0%b0%d0%b5%d0%bc-%d0%bc%d1%84%d1%83-pantum/"
---

Есть такие новые китайские принтеры (относительноо новые) Pantum. В них и wifi, и поддержка linux из коробки. Достал я и себе такое чудо платы лутом делать и шаблоны для фоторезиста печатать.

Настроить wifi в принтере (если он есть) без смартфона не получится - лезем туда и делаем все по инструкции (она с картинками).

Теперь надо поставить драйвера и настроить сканирование и печать по wifi. Если у вас убунту или дебиан, то драйвера скачиваются и устанавливаются в виде пакета. Если федора, то придется немного покомпилировать.

**Пакеты для fedora**

Предварительно потребуется собрать библиотеку libjpeg so.8. Иначе вы не сможете поставить sane-драйвера.

```
nothing provides libjpeg so.8()(64bit) needed by pantum-m6xxx-sane-1 4 0-2 fc28 x86_64
```

```shell
$ sudo dnf install rpm-build spectool git
$ rpmdev-setuptree
$ git clone https://github.com/RussianFedora/compat-libjpeg8.git
$ cd compat-libjpeg8
$ spectool --all --get-files --directory ~/rpmbuild/SOURCES compat-libjpeg8.spec
$ rpmbuild -bb compat-libjpeg8.spec
$ sudo dnf install ~/rpmbuild/RPMS/$(uname -m)/compat-libjpeg8-1.5.3-3.fc32.x86_64.rpm
```

А теперь можно собирать и ставить драйвера для pantum.

```shell
$ sudo dnf install rpm-build spectool git
$ rpmdev-setuptree
$ git clone https://github.com/EasyCoding/pantum-m6xxx.git
$ cd pantum-m6xxx
$ spectool --all --get-files --directory ~/rpmbuild/SOURCES pantum-m6xxx.spec
$ rpmbuild -bb pantum-m6xxx.spec
$ sudo dnf install ~/rpmbuild/RPMS/$(uname -m)/pantum-m6xxx-*.rpm
```

**Настройка cups**

Заходим в [веб-интерфейс](http://localhost:631/) и добавляем принтер. Никаких сложностей нет.

**Настройка sane**

Тут сложнее. Ставим sane-backend, а потом добавляем удаленный сканер.

```shell
sudo dnf install sane-backends-daemon
```

Добавляем в /etc/sane.d/dll.conf

```text
pantum6500
pantum_mfp
```

Получаем доменный адрес принтера через avahi. Я уже [писал](https://russianpenguin.ru/2016/04/08/%d0%ba%d0%b0%d0%ba-%d0%b6%d0%b8%d1%82%d1%8c-%d0%b2-%d0%bb%d0%be%d0%ba%d0%b0%d0%bb%d1%8c%d0%bd%d0%be%d0%b9-%d1%81%d0%b5%d1%82%d0%b8-%d0%b1%d0%b5%d0%b7-dns/) о том, как работать без локального днс.

```shell
$ avahi-browse-domains -alr
```

Среди полученных адресов вы увидите адрес принтера Pantum-XXXXXX.local. Этот адрес надо добавить в /etc/sane.d/net.conf.

Запускаем saned.

```shell
$ sudo systemctl start saned.socket
```

Проверяем наличие сканера.

```shell
$ scanimage -L 
device `v4l:/dev/video0' is a Noname HD WebCam: HD WebCam virtual device 
device `airscan:e0:Pantum M6500W series[XXXXX]' is a eSCL Pantum M6500W series[XXXXX] eSCL network scanner
```

**Литература**
* [Собираем пакет с драйверами МФУ Pantum для Fedora](https://www.easycoding.org/2018/02/11/sobiraem-paket-s-drajverami-mfu-pantum-dlya-fedora.html)
* [Xsane from a network MFP](https://forums.linuxmint.com/viewtopic.php?p=1204049&sid=68afd6af54d2b9f15fa0394439fe43ac#p1204049)
* [SaneOverNetwork](https://wiki.debian.org/SaneOverNetwork)
