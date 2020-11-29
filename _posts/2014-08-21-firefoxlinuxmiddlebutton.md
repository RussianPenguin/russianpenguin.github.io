---
layout: post
title: Firefox+Linux+MiddleButton
date: 2014-08-21 14:36:13.000000000 +04:00
type: post
parent_id: '0'
published: true
password: ''
status: publish
categories:
- HowTo
tags:
- firefox
- linux
meta:
  _publicize_pending: '1'
  _wpcom_is_markdown: '1'
  sharing_disabled: '1'
  _rest_api_published: '1'
  _rest_api_client_id: "-1"
  _wpas_skip_facebook: '1'
  _wpas_skip_google_plus: '1'
  _wpas_skip_twitter: '1'
  _wpas_skip_linkedin: '1'
  _wpas_skip_tumblr: '1'
  _wpas_skip_path: '1'
  _edit_last: '13696577'
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2014/08/21/firefoxlinuxmiddlebutton/"
---
Есть такая особенность у сборки Firefox в Linux (как под виндой - не знаю) - если нажать среднюю кнопку (не на ссылке, а просто в пределах страницы), то в адресную строку будет вставлено содержимое буфера обмена.

Очень и очень раздражающая фича.

Но отключается она очень просто.

1. Идем в [about:config](config "about:config").
2. Находим настройку **middlemouse.contentLoadURL** и выставляем ее в **false** (или создаем - это параметр "логическое")

Вопреки советам в сети за подобное поведение параметр **middlemouse.paste** не отвечает.

