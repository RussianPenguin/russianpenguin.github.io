---
layout: post
title: 'Linux: связываем приложение с типами файлов'
type: post
categories: []
tags:
- консоль
- linux
permalink: "/2016/01/04/linux-%d1%81%d0%b2%d1%8f%d0%b7%d1%8b%d0%b2%d0%b0%d0%b5%d0%bc-%d0%bf%d1%80%d0%b8%d0%bb%d0%be%d0%b6%d0%b5%d0%bd%d0%b8%d0%b5-%d1%81-%d1%82%d0%b8%d0%bf%d0%b0%d0%bc%d0%b8-%d1%84%d0%b0%d0%b9%d0%bb%d0%be/"
---

**Статья дополнена 2022-03-08**

![xdg]({{ site.baseurl }}/assets/images/2016/01/xdg.png)Не секрет, что для новичков в никсах существует лишь один путь для выбора приложения, которым будет открываться какой-либо тип файлов: конфигуратор его рабочей среды (кеды, гном, xfce или иное).  
Однако то, что происходит за кадром пользователю остается неизвесным. И как только юный падаван попадает в голые иксы с запущенным xterm или голым, но от этого не менее дружелюбным, оконным менеджером (openbox, fluxbox, xmonad и т.д.) - у него сразу возникает куча проблема.

- почему все мои файловые ассоциации, которые я так долго настраивал исчезли?
- Почему в mc все картинки и видео вдруг начинают открываться в браузере?
- почему они вообще открываются через mc?
- почему firefox при выборе пункта "открывать файл" вместо сохранить открываеть его непонятно где или вообще не открывает?

И новичок это гиблое дело забрасывает и возвращается в удобные кеды, гном или что-то еще.

Но на самом деле не все так страшно.

Современные [стандарты freedesktop](http://standards.freedesktop.org/desktop-entry-spec/latest/) указывают нам на то, что запуск приложений осуществляется с помощью *.desktop файлов, которые описывают все, что необходимо для работы приложения.

А чтобы связать тип файла с приложением, которое будет запускаться введен стандарт [Association between MIME types and applications](http://standards.freedesktop.org/mime-apps-spec/mime-apps-spec-1.0.html).

Этот стандарт описывает ряд файлов, которые отвечают за связь меджу типом файла и приложением.

| Путь | Предназначение |
| --- | --- |
| `$HOME/.config/$desktop-mimeapps.list` | Пользовательские ассоциации. Специфичные для рабочего стола $desktop |
| `$HOME/.config/mimeapps.list` | Пользовательские ассоциации (независимы от рабочего стола) |
| `/etc/xdg/$desktop-mimeapps.list` | Глобальные ассоциации. Предоставляются администратором. специфичные для рабочего стола $desktop |
| `/etc/xdg/mimeapps.list` | Глобальные ассоциации, предоставляемые админом и вендорами ПО. |
| `$HOME/.local/share/applications/$desktop-mimeapps.list` | Глобальные системные ассоциации. Специфичны для рабочего стола $desktop. Запрещен к использованию. Будет удален в новых редакциях стандарта. |
| `$HOME/.local/share/applications/mimeapps.list` | Глобальные системные ассоциации. Запрещен к использованию. Будет удален в новых редакциях стандарта. |
| `/usr/local/share/applications/$desktop-mimeapps.list` and  
`/usr/share/applications/$desktop-mimeapps.list` | Набор ассоциаций, которые предоставляются мейнтейнерами дистрибутива. Специфичны для рабочего стола $desktop. |
| `/usr/local/share/applications/mimeapps.list` and  
`/usr/share/applications/mimeapps.list` | Набор ассоциаций, которые предоставляются мейнтейнерами дистрибутива. |

Таблица описывает файлы в том порядке, в котором они обрабатываются системой. Переменная $desktop представляет из себя имя рабочего стола в нижнем регистре (kde, gnome, xfce, ...).

Данные файлы представляют из себя набор записей вида

```
[Default Applications]  
mimetype1=default1.desktop;default2.desktop
```

mimetype - описание формата. Что-то вроде audio/ogg. Стандарт описания mimetype можно глянуть в [соответствующих RFC](https://en.wikipedia.org/wiki/MIME).  
*.desktop есть файл запуска вашего приложения. Обрабатывается список файлов последовательно до первого встреченного существующего приложения. Либо система перейдет к обработке следующего файла.

Помимо основной секции стандарт оговаривает две дополнительных секции.

```
[Added Associations]  
mimetype1=foo1.desktop;foo2.desktop;foo3.desktop  
mimetype2=foo4.desktop  
[Removed Associations]  
mimetype1=foo5.desktop  

```

Секция "added associations" добавляет к выбранным mime-типам указанные приложения в начало списка. Секция "removed association" соотственно удаляет указанные приложения из ассоциации к выбранному mime-типу.

Все. с теорией покончено.

Как было сказано выше - в "дружелюбном окружении уже существует какая-нибудь утилита, которая позволяет пользователю изменить ассоциации.

Но гораздо проще делать это в консоли.

Существует инструмент под названием xdg, который как раз отвечает за работу со списками ассоциаций. И большинство приложений как раз используют его api дабы открывать файлы (mc, nautilus, firefox, ...).

Попробуем сделать в консоли

```
$ xdg-open ~/some_path_to_image.jpg
```

Вы увидите, что картинка откроется при помощи стандартного вьювера для вашего рабочего стола.

А теперь попробуйте сделать

```
$ xdg-mime query default image/jpg
```

Вы увидите что-то вроде

```
eog.desktop;
```

xdg-mime - инструмент, который входит в комплект поставки любого дистрибутива. Им можно как просматривать, так и изменять ассоциации файлов.

Для примера узнаем, как система распознает какую-нибудь картинку.

```
% xdg-mime query filetype wallpaper.jpg
```

Увидим

```
image/jpeg
```

**Shared MIME database**

Это спецификация, которая позволяет приложениям легче прописывать в систему информацию о том, какими расширениями файлов они могут манипулирвать.

Для целей статьи это неинтересно, но почитать можно [тут](https://specifications.freedesktop.org/shared-mime-info-spec/shared-mime-info-spec-latest.html).

**Как работает привязка файлов в ручном режиме**

Посмотрим, что происходит под капотом. Сейчас это не самый лучший способ. О более простом варианте речь пойдет чуть дальше.

Допустим, что у нас свежезаведенный профиль.

```
$ cat ~/.local/share/applications/mimeapps.list  
[Default Applications]  

```

У вас этого файла может не быть, либо он может содержать какие-то дефолтные значения.

А теперь мы хотим, чтобы файлы mp4 открывались при помощи vlc.

```
$ xdg-mime default vlc.desktop video/mp4  
$ cat ~/.local/share/applications/mimeapps.list

[Default Applications]  
video/mp4=vlc.desktop
```

Как видим - используется vlc. И если мы попробуем сделать

```
$ xdg-open path_to_mp4_file.mp4
```

Файл откроется уже в vlc.

**Автоматизированный способ привязки**

Сначала нам нужно понять, какие приложения поддерживают нужный mime.

Каждый *.desktop-файл содержит записи mime-типов, которые он поддерживает.

```shell
$ cat /usr/share/applications/pcmanfm.desktop | grep -i mime
MimeType=inode/directory;
```

Теперь нам нужно лишь грепнуть все desktop чтобы найти, что нам надо.

Допустим, что нам хочется переопределить приложение для открытия папок. Найдем, кто их может открывать.

```shell
$ rgrep "inode/directory" /usr/share/applications
/usr/share/applications/mimeinfo.cache:inode/directory=org.gnome.baobab.desktop;pcmanfm.desktop;ranger.desktop;
/usr/share/applications/org.gnome.baobab.desktop:MimeType=inode/directory;
/usr/share/applications/mimeapps.list:inode/directory=org.gnome.Nautilus.desktop
/usr/share/applications/pcmanfm.desktop:MimeType=inode/directory;
/usr/share/applications/gnome-mimeapps.list:inode/directory=org.gnome.Nautilus.desktop
/usr/share/applications/ranger.desktop:MimeType=inode/directory;
```

Тут мы видим, что папками манипулируют ranger, baobab и pcmanfm.

Для привязки приложения и типа нам так же поможет xdg-mime.

```
xdg-mime default application mimetype(s)
```

```shell
$ xdg-mime default pcmanfm.desktop inode/directory
```

Тем самым мы связали тип ```inode/directory``` с приложением pcmanfm. Стоит заметить, что указывать путь до *.desktop не надо. Он будет разыскиваться по стандартным путям, которые мы обсудили выше.

**Замечания**

Искать кто может открыть какой-то файл долго и сложно. Можно воспользоваться утилитой [lsdesktopf](https://github.com/AndyCrowd/list-desktop-files).

**Литература**:
* [XDG MIME Applications](https://wiki.archlinux.org/title/XDG_MIME_Applications)
* [https://wiki.archlinux.org/index.php/Default_applications](https://wiki.archlinux.org/index.php/Default_applications)  
* [https://wiki.archlinux.org/index.php/Desktop_entries](https://wiki.archlinux.org/index.php/Desktop_entries)

