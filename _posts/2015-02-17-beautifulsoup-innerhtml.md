---
layout: post
title: BeautifulSoup innerHTML
date: 2015-02-17 21:54:08.000000000 +03:00
type: post
parent_id: '0'
published: true
password: ''
status: publish
categories:
- Обработка текста
- Разработка
tags:
- python
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
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2015/02/17/beautifulsoup-innerhtml/"
---
Функция в рамках браузеров по сей день остается недокументированной. А значит на стороне большинства парсеров она не реализована.

Но как быть если хочется?

```python
def innerHTML(element):  
 return element.decode\_contents(formatter="html")
```

