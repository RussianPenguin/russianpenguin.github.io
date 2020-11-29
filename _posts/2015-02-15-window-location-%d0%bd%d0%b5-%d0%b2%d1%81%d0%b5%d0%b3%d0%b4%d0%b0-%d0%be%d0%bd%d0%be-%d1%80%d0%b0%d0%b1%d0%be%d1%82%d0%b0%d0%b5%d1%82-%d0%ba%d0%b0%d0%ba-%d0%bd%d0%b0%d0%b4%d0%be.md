---
layout: post
title: window.location - не всегда оно работает как надо
date: 2015-02-15 16:01:40.000000000 +03:00
type: post
parent_id: '0'
published: true
password: ''
status: publish
categories:
- Разработка
tags:
- баги
- javascript
meta:
  _wpcom_is_markdown: '1'
  sharing_disabled: '1'
  _wpas_skip_facebook: '1'
  _wpas_skip_google_plus: '1'
  _wpas_skip_twitter: '1'
  _wpas_skip_linkedin: '1'
  _wpas_skip_tumblr: '1'
  _wpas_skip_path: '1'
  _publicize_pending: '1'
  _rest_api_published: '1'
  _rest_api_client_id: "-1"
  _edit_last: '13696577'
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2015/02/15/window-location-%d0%bd%d0%b5-%d0%b2%d1%81%d0%b5%d0%b3%d0%b4%d0%b0-%d0%be%d0%bd%d0%be-%d1%80%d0%b0%d0%b1%d0%be%d1%82%d0%b0%d0%b5%d1%82-%d0%ba%d0%b0%d0%ba-%d0%bd%d0%b0%d0%b4%d0%be/"
---
Если раньше мы долго ругались на проблемы IE, то сейчас на FF и Chrome - ничего особо не поменялось.

На этот раз отличились механизмы работы с хешом в window.location.

Кейс: мы хотим перенаправить пользователя с помощью js на другую страницу.

Что может пойти не так?

Например у нас есть ссылка вида /foo. И логично предположить, что в современном мире мы хотим сохранить хеш (это нужно для angularjs например) чтобы на новой странице отработал нужный функционал.

Что мы делаем

```javascript
window.location.pathname = '/foo'
```

Работает.

Но стоит только в ссылку нечаянно попасть хешу #, как поведение браузеров сразу резко меняется.

```javascript
window.location.pathname='/foo#bar'
```

Chrome среагирует правильно и отправит нас по ссылке /foo%23bar, а Firefox - нет. У него будет ссылка /foo#bar.

Ниже иллюстрации.

[![FF: window.location.pathname bug]({{ site.baseurl }}/assets/images/2015/02/d0b2d18bd0b4d0b5d0bbd0b5d0bdd0b8d0b5_175.png?w=300)](https://russianpenguin.files.wordpress.com/2015/02/d0b2d18bd0b4d0b5d0bbd0b5d0bdd0b8d0b5_175.png)[![Chrome: window.location.pathname]({{ site.baseurl }}/assets/images/2015/02/d0b2d18bd0b4d0b5d0bbd0b5d0bdd0b8d0b5_177.png?w=300)](https://russianpenguin.files.wordpress.com/2015/02/d0b2d18bd0b4d0b5d0bbd0b5d0bdd0b8d0b5_177.png)Как видим FF с задачей не справился. И не перекодировал # автоматом.

[Баг](https://bugzilla.mozilla.org/show_bug.cgi?id=483304 "FF: window.location.hash bug") этот давний - аж 2009го года.

Поэтому внимательно следите за редиректами если у вас есть спецсимволы в урле.

Проще говоря: pathname в FF вседет себя так же, как и href (за мелким исключением вроде сохранения хеша).

Исходники на посмотреть [тут](https://github.com/RussianPenguin/blogSamples/blob/master/pathname.php "Исходники к статье").

