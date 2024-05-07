---
layout: post
title: 'Fedora: сборка пакетов из src.rpm'
date: 2018-05-19 14:44:23.000000000 +03:00
type: post
categories:
- HowTo
tags:
- linux
- rpm
permalink: "/2018/05/19/fedora-%d1%81%d0%b1%d0%be%d1%80%d0%ba%d0%b0-%d0%bf%d0%b0%d0%ba%d0%b5%d1%82%d0%be%d0%b2-%d0%b8%d0%b7-src-rpm/"
excerpt: Разбираемся, что это за пакеты src.rpm и как поставить софт из исходников.
---
![2018-05-19-12:35:39_1076x631]({{ site.baseurl }}/assets/images/2018/05/2018-05-19-123539_1076x631.png){:.img-fluid}

Чаще всего то не требуется обычному пользователю. Но бывает ситуации, когда пакет собран с поддержкой библиотеки исключенной из дистрибутива.

Недавно это случилось с chromium в centos, а с драйверами от epson случается постоянно.

```shell
$ cd imagescan-bundle-fedora-27-1.3.23.x64.rpm/  
$ ./install.sh  
[sudo] пароль для penguin:  
Последняя проверка окончания срока действия метаданных: 2:53:00 назад, Сб 19 мая 2018 09:42:23.  
Ошибка:  
 Проблема 1: conflicting requests  
 - nothing provides libboost_filesystem.so.1.64.0()(64bit) needed by imagescan-3.33.0-1epson4fedora27.x86_64  
 Проблема 2: package imagescan-plugin-networkscan-1.1.1-1epson4fedora27.x86_64 requires imagescan >= 3.9.0, but none of the providers can be installed  
 - conflicting requests  
 - nothing provides libboost_filesystem.so.1.64.0()(64bit) needed by imagescan-3.33.0-1epson4fedora27.x86_64  
 Проблема 3: package imagescan-plugin-gt-s650-1.0.0-1epson4fedora27.x86_64 requires imagescan >= 3.28.0, but none of the providers can be installed  
 - conflicting requests  
 - nothing provides libboost_filesystem.so.1.64.0()(64bit) needed by imagescan-3.33.0-1epson4fedora27.x86_64  
 Проблема 4: package imagescan-plugin-ocr-engine-1.0.0-1epson4fedora27.x86_64 requires imagescan >= 3.14.0, but none of the providers can be installed  
 - conflicting requests  
 - nothing provides libboost_filesystem.so.1.64.0()(64bit) needed by imagescan-3.33.0-1epson4fedora27.x86_64
```

А еще это может потребоваться если мы хотим поставить пакет, который распространяется только в src.rpm.

<!--more-->

## Устанавливаем тулчейн для сборки

Нам потребуется группа пакетов для сборки RPM

```shell
$ sudo dnf group install "RPM Development Tools"
```

## Подготавливаем окружение

```shell
$ rpmdev-setuptree
```

Команда подготовит необходимую структуру папок в домашнем каталого.

```
/home/penguin/rpmbuild/  
├── BUILD  
├── BUILDROOT  
├── RPMS  
├── SOURCES  
├── SPECS  
└── SRPMS
```

## Достаем пакет с исходниками

Вы же видели репозитарии source (например, _russianfedora-nonfree-updates-testing-source_. Все эти репозитарии откючены в конфиге и включать их нет необходимости потому что пакеты из них обычным способом поставить нельзя.

Установка src.rpm осуществляется под аккаунтом пользователя и производится в каталог rpmbuild, который был подготовлен выше.

Сначала пакет нужно скачать (либо руками, либо из репозитария). В случае репозитария это делается через dnf.

```shell
$ dnf download --source xmoto
```

## Установка зависимостей

Для сборки многим пакетам требуются заголовочые файлы или библиотеки для линковки, которые принадлежат другим пакетам. Все зависимости описываются в самом файле с исходниками и для их установки нужно только вызвать команду builddep.

```shell
$ sudo dnf builddep package.src.rpm
```

## Установка исходников

Теперь пакет нужно поставить (не забываем, что все операции выполняются под аккаунтом текущего пользователя, а не рута).

```shell
$ rpm -ivh imagescan-3.33.0-1epson4fedora27.src.rpm
```

## Сборка

Для сборки следует проверить, что спецификация нужного  пакета появилась в каталоге ~/rpmbuild/SPECS и собрать его при помоощи rpmbuild. Сначала используем опцию -bp, которая выполнит подготовку к сборке и тем самым мы сможем убедиться (хотя бы теоретически), что тулчейн заработал и это вообще можно собрать. И тольео после того, как все прошло удачно заюзаем -ba или -bb.

```shell
$ cd ~/rpmbuild  
$ rpmbuild -bp SPECS/imagescan.spec
```

```shell
$ rpmbuild -bp SPECS/imagescan.spec  
ошибка: uversion undefined, define to match source archive  
ошибка: строка 2: %{!?uversion: %{error: uversion undefined, define to match source archive}}
```

В случае imagescan весь процесс происходит очень болезненно. Поэтому я пропущу детали поиска решения и лишь покажу процесс.

```shell
$ rpmbuild -bp --define "uversion 0.33.0" SPECS/imagescan.spec
```

Дополнительная опция --define позволяет определять и переопределять макросы, которые будут использоваться тулчейном.

```
/usr/include/gtk-2.0/gtk/gtkstatusicon.h:76:8: error: unnecessary parentheses in declaration of '__gtk_reserved1' [-Werror=parentheses]  
void (*__gtk_reserved1);  
^  
/usr/include/gtk-2.0/gtk/gtkstatusicon.h:77:8: error: unnecessary parentheses in declaration of '__gtk_reserved2' [-Werror=parentheses]  
void (*__gtk_reserved2);  
^  
cc1plus: all warnings being treated as errors  
make[2]: *** [Makefile:573: dialog.lo] Error 1  
make[2]: Leaving directory '/home/penguin/rpmbuild/BUILD/utsushi-0.33.0/gtkmm'  
make[1]: *** [Makefile:604: all-recursive] Error 1  
make[1]: Leaving directory '/home/penguin/rpmbuild/BUILD/utsushi-0.33.0'  
make: *** [Makefile:511: all] Error 2  
ошибка: Неверный код возврата из /var/tmp/rpm-tmp.hKyez0 (%build)
```

Таких ошибок встретится превеликое множество из-за довольно старых исходников, которые не адаптированы под свежий стандарт c++. Решением будет установка целой группы флагов через глобальную переменную CXXFLAGS.

```shell
CXXFLAGS="-fPIC -Wno-parentheses -Wno-sizeof-pointer-div" rpmbuild -bb --define "uversion 0.33.0" --define "debug_package %{nil}" SPECS/imagescan.spec
```

Флаг debug_package добавлен из-за того, что попытка сборки пакета debugpackage приподит к ошибке из-за отсутствия нужных определений в файле спецификации.

## Установка пакета

```shell
$ sudo dnf install RPMS/x86_64/imagescan-3.33.0-1.fc28.x86_64.rpm
```

## Литература

- [Re: Disabling warning: suggest parentheses around && within ||](https://gcc.gnu.org/ml/gcc-help/2009-05/msg00118.html)
- [Passing conditional parameters into a rpm build](http://ftp.rpm.org/api/4.4.2.2/conditionalbuilds.html)
- [Manually Prepare the RPM Building Environment](https://www.g-loaded.eu/2009/04/24/manually-prepare-the-rpm-building-environment/)
- [How do I install a src rpm with dnf?](https://ask.fedoraproject.org/en/question/87205/how-do-i-install-a-src-rpm-with-dnf/)
- [Установка софта из "сырых" RPM](http://knoppix.ru/adv090703.shtml)
- [3.8 Options to Request or Suppress Warnings](https://gcc.gnu.org/onlinedocs/gcc-4.8.2/gcc/Warning-Options.html)
- [rpmbuild: how to skip generation of “debuginfo” packages (without change SPEC file ; neither .rpmmacros)](https://stackoverflow.com/questions/36983051/rpmbuild-how-to-skip-generation-of-debuginfo-packages-without-change-spec-fi)

 

