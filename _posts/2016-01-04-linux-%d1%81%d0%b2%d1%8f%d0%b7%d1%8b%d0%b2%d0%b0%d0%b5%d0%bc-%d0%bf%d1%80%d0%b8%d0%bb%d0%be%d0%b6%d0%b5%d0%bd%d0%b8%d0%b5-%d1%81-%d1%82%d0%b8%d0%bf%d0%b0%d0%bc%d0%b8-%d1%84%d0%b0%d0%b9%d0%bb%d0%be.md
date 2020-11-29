---
layout: post
title: 'Linux: связываем приложение с типами файлов'
date: 2016-01-04 20:21:02.000000000 +03:00
type: post
parent_id: '0'
published: true
password: ''
status: publish
categories: []
tags:
- консоль
- linux
meta:
  _wpcom_is_markdown: '1'
  _oembed_1f8053068ecd77ce9d5288c227e64916: "{{unknown}}"
  _oembed_cc73ed0eec100d7bea168b2c37d506bc: "{{unknown}}"
  _rest_api_published: '1'
  _rest_api_client_id: "-1"
  _publicize_job_id: '18408581230'
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2016/01/04/linux-%d1%81%d0%b2%d1%8f%d0%b7%d1%8b%d0%b2%d0%b0%d0%b5%d0%bc-%d0%bf%d1%80%d0%b8%d0%bb%d0%be%d0%b6%d0%b5%d0%bd%d0%b8%d0%b5-%d1%81-%d1%82%d0%b8%d0%bf%d0%b0%d0%bc%d0%b8-%d1%84%d0%b0%d0%b9%d0%bb%d0%be/"
---
![xdg]({{ site.baseurl }}/assets/images/2016/01/xdg.png)Не секрет, что для новичков в никсах существует лишь один путь для выбора приложения, которым будет открываться какой-либо тип файлов: конфигуратор его рабочей среды (кеды, гном, xfce или иное).  
Однако то, что происходит за кадром пользователю остается неизвесным. И как только юный падаван попадает в голые иксы с запущенным xterm или голым, но от этого не менее дружелюбным, оконным менеджером (openbox, fluxbox, xmonad и т.д.) - у него сразу возникает куча проблема.

- почему все мои файловые ассоциации, которые я так долго настраивал исчезли?
- Почему в mc все картинки и видео вдруг начинают открываться в браузере?
- почему они вообще открываются через mc?
- почему firefox при выборе пункта "открывать файл" вместо сохранить открываеть его непонятно где или вообще не открывает?

И новичок это гиблое дело забрасывает и возвращается в удобные кеды, гном или что-то еще.

Но на самом деле не все так страшно.

Современные [стандарты freedesktop](http://standards.freedesktop.org/desktop-entry-spec/latest/) указывают нам на то, что запуск приложений осуществляется с помощью \*.desktop файлов, которые описывают все, что необходимо для работы приложения.

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
\*.desktop есть файл запуска вашего приложения. Обрабатывается список файлов последовательно до первого встреченного существующего приложения. Либо система перейдет к обработке следующего файла.

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
$ xdg-open ~/some\_path\_to\_image.jpg
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

А теперь самое главное - привязываем приложения к своим типам файлов. И заодно посмотрим, что происходит под капотом.

Допустим, что у нас свежевыкращенныйзаведенный профиль.

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
$ xdg-open path\_to\_mp4\_file.mp4
```

Файл откроется уже в vlc.

Почитать:  
[https://wiki.archlinux.org/index.php/Default\_applications](https://wiki.archlinux.org/index.php/Default_applications)  
[https://wiki.archlinux.org/index.php/Desktop\_entries](https://wiki.archlinux.org/index.php/Desktop_entries)

