---
layout: post
title: 'Linux: Включение субпиксельного рендеринга'
date: 2013-11-03 22:58:18.000000000 +04:00
type: post
parent_id: '0'
published: true
password: ''
status: publish
categories:
- HowTo
tags:
- linux
meta:
  _publicize_pending: '1'
  _edit_last: '13696577'
  original_post_id: '6'
  _wp_old_slug: '6'
  _wpcom_is_markdown: '1'
  geo_public: '0'
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2013/11/03/linux-enable-subpixel-rendering/"
---
[caption id="attachment\_7" align="alignleft" width="250"][![Вот так выглядят шрифты с отключенным субпиксельным рендерингом]({{ site.baseurl }}/assets/images/2013/11/004.png "Шрифты по умолчанию")](http://russianpenguin.files.wordpress.com/2013/11/004.png) Вот так выглядят шрифты с отключенным субпиксельным рендерингом[/caption]

По умолчанию шрифты в линупсе более чем УГ. За исключением убунту и "пропатченных" дистрибутивов, которые не подвластны патентным тролям из яблочной корпорации.

И если в убунту субпиксельный рендеринг включен, то для его включения в других дистрибутивах нужно потанцевать с бубном.<!--more-->

Для RedHat и производных:

Подключить репозитарии [RPMFusion](http://rpmfusion.org/Configuration "RPMFusion configuration") (nonfree) и скомандовать

```
sudo yum install freetype-freeworlddpkg-reconfigure fontconfig-config
```

Последовательно выбираем

- способ подстройки - native (лучше подходит для отрисовки шрифтов dejavu\*). тут можно экспериментировать.
- "использовать субпиксельный рендеринг - всегда
- включить растровые шрифты по умолчанию - нет

Все. Теперь у нас нормальные шрифты (в красной шапке и иже надо перезагрузиться). Недостает еще рецепта для SuSE. Если буду с ней работать - добавлю.

[![Включенный субпиксельный рендеринг]({{ site.baseurl }}/assets/images/2013/11/005.png)](http://russianpenguin.files.wordpress.com/2013/11/005.png)

