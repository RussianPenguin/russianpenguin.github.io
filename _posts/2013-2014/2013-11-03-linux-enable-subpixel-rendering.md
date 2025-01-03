---
layout: post
title: 'Linux: Включение субпиксельного рендеринга'
type: post
categories:
- HowTo
tags:
- linux
permalink: "/2013/11/03/linux-enable-subpixel-rendering/"
---
![Вот так выглядят шрифты с отключенным субпиксельным рендерингом]({{ site.baseurl }}/assets/images/2013/11/004.png "Шрифты по умолчанию"){:.img-fluid}

Вот так выглядят шрифты с отключенным субпиксельным рендерингом

По умолчанию шрифты в линупсе более чем УГ. За исключением убунту и "пропатченных" дистрибутивов, которые не подвластны патентным тролям из яблочной корпорации.

И если в убунту субпиксельный рендеринг включен, то для его включения в других дистрибутивах нужно потанцевать с бубном.<!--more-->

Для RedHat и производных:

Подключить репозитарии [RPMFusion](http://rpmfusion.org/Configuration "RPMFusion configuration") (nonfree) и скомандовать

```
sudo yum install freetype-freeworlddpkg-reconfigure fontconfig-config
```

Последовательно выбираем

- способ подстройки - native (лучше подходит для отрисовки шрифтов dejavu*). тут можно экспериментировать.
- "использовать субпиксельный рендеринг - всегда
- включить растровые шрифты по умолчанию - нет

Все. Теперь у нас нормальные шрифты (в красной шапке и иже надо перезагрузиться). Недостает еще рецепта для SuSE. Если буду с ней работать - добавлю.

![Включенный субпиксельный рендеринг]({{ site.baseurl }}/assets/images/2013/11/005.png){:.img-fluid}

