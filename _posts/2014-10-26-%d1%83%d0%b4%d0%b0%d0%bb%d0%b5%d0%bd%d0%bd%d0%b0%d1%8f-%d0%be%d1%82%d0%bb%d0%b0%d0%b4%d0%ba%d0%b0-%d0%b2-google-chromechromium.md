---
layout: post
title: Удаленная отладка в Google Chrome/Chromium
date: 2014-10-26 13:23:06.000000000 +03:00
type: post
parent_id: '0'
published: true
password: ''
status: publish
categories:
- Разработка
tags:
- отладка
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
  _oembed_55030f2636c234d3cdb0eff854a01325: "{{unknown}}"
  _publicize_pending: '1'
  _rest_api_published: '1'
  _rest_api_client_id: "-1"
  _oembed_276359bcd8779682e0f93bbc9055cb9a: "{{unknown}}"
  _edit_last: '13696577'
  _oembed_71cb3a9b2c68a359237a17b2a336d8f2: "{{unknown}}"
  _oembed_b62b08e655c7ae602a74102f807ea8a7: "{{unknown}}"
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2014/10/26/%d1%83%d0%b4%d0%b0%d0%bb%d0%b5%d0%bd%d0%bd%d0%b0%d1%8f-%d0%be%d1%82%d0%bb%d0%b0%d0%b4%d0%ba%d0%b0-%d0%b2-google-chromechromium/"
---
Пока рассмотрим только удаленную отладку комп-комп. Иногда это бывает очень необходимо.

На подопытном запускаем

```shell
$ chromium --remote-debugging-port=9222
```

На машине с отдадчиком заходим (ессно в хроме) на урл

http://target-machine:9999

Выбираем страницу - отлаживаем что надо.

К сожалению нельзя осуществлять взаимодействие с подопытной страницей напрямую из отладчика (конечно же можно, если мы будем делать что-то вроде $('element').trigger('event-name') в консоли), но можно перезагружать страницу.

Поэтому на подопытном запускаем тимвьювер или внц и отлаживаем чего надо.

Еще можно добавить параметр --user-data-dir=\<тут путь\>. Эта опция нужна если для отладки вы хотите пользовать как-то особым образом сконфигурированный профиль.

[![Удаленная отладка с Chrome]({{ site.baseurl }}/assets/images/2014/10/d180d0b0d0b1d0bed187d0b5d0b5-d0bcd0b5d181d182d0be-2_098.png?w=300)](https://russianpenguin.files.wordpress.com/2014/10/d180d0b0d0b1d0bed187d0b5d0b5-d0bcd0b5d181d182d0be-2_098.png)

