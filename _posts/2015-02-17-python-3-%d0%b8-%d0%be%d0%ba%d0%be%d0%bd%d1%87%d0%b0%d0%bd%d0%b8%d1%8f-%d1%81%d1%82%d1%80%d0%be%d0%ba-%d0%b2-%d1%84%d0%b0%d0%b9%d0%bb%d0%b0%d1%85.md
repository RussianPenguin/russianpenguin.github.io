---
layout: post
title: Python 3+ и окончания строк в файлах
date: 2015-02-17 23:53:18.000000000 +03:00
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
  _wpcom_is_markdown: '1'
  _publicize_pending: '1'
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
permalink: "/2015/02/17/python-3-%d0%b8-%d0%be%d0%ba%d0%be%d0%bd%d1%87%d0%b0%d0%bd%d0%b8%d1%8f-%d1%81%d1%82%d1%80%d0%be%d0%ba-%d0%b2-%d1%84%d0%b0%d0%b9%d0%bb%d0%b0%d1%85/"
---
Столкнулся одного теста, который был перенесен с python 2+ на python 3+.

Тест делал следующее:

скачивал файл через python.requests и сравнивал его с эталонным содержимым на диске (посимвольно).

Выглядело приблизительно так

[code language="python"]import requests  
import sys  
response = requests.get(sys.argv[1])  
if response.code == 200:  
 with open(sys.argv[2]) as f:  
 from\_storage = f.read()  
 from\_web = response.text  
 assert from\_web == from\_storage[/code]

Да. Все верно. Этот тест не проходил.

И тут была замечена одна странность: файл на диске содержал последовательность crlf, а в coдержимом from\_storage этой последовательности не оказало.

А дело все в том, что в python 3+ было введено [соглашение](https://docs.python.org/release/3.2/library/functions.html#open "Python 3+ - open function") на обработку символов перевода строки. И управление работой осуществляется манипулированием параметром newline.

- On input, if newline is None, universal newlines mode is enabled. Lines in the input can end in '\n', '\r', or '\r\n', and these are translated into '\n' before being returned to the caller. If it is '', universal newline mode is enabled, but line endings are returned to the caller untranslated. If it has any of the other legal values, input lines are only terminated by the given string, and the line ending is returned to the caller untranslated.
- On output, if newline is None, any '\n' characters written are translated to the system default line separator, os.linesep. If newline is '', no translation takes place. If newline is any of the other legal values, any '\n' characters written are translated to the given string.

В итоге достаточно было указать newline='' как CRLF появились.

