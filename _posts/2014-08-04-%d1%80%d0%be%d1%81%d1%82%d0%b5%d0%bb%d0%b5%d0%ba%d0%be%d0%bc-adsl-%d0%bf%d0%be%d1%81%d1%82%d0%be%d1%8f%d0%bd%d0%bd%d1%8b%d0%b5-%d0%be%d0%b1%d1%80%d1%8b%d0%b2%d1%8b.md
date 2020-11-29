---
layout: post
title: 'Ростелеком: постоянные обрывы соединения'
date: 2014-08-04 11:00:07.000000000 +04:00
type: post
parent_id: '0'
published: true
password: ''
status: publish
categories:
- HowTo
tags:
- adsl
- очевидное-невероятное
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
  _wp_old_slug: "%d1%80%d0%be%d1%81%d1%82%d0%b5%d0%bb%d0%b5%d0%ba%d0%be%d0%bc-%d0%bf%d0%be%d1%81%d1%82%d0%be%d1%8f%d0%bd%d0%bd%d1%8b%d0%b5-%d0%be%d0%b1%d1%80%d1%8b%d0%b2%d1%8b-%d1%81%d0%be%d0%b5%d0%b4%d0%b8%d0%bd"
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2014/08/04/%d1%80%d0%be%d1%81%d1%82%d0%b5%d0%bb%d0%b5%d0%ba%d0%be%d0%bc-adsl-%d0%bf%d0%be%d1%81%d1%82%d0%be%d1%8f%d0%bd%d0%bd%d1%8b%d0%b5-%d0%be%d0%b1%d1%80%d1%8b%d0%b2%d1%8b/"
---
Все было бы нормально если бы не одно но.

Адсл-модем уверенно устанавливал соединение и. И что самое интересное просто так обрывал pppoe-коннект с фразой "pppoe connection terminated unexpectedly".

Расследование показало, что перед тем, как соединение будет сброшено в лог (что немаловажно в отладочный дл которого надо повысить уровень отладочных сообщений до dbg) падало сообщение о том, что "no response on 3 echo requests".

Выяснилось следующее:

- падение происходило каждые полторы минуты
- в конфиге для текущего соединения найдена строка lcp echo 30 3

О как! Оказывается, что модем проверяет жизнеспособность соединения при помощи lcp-запросов, а выяснилось, что ростелекомовский adsl их рубит сразу.

Вывод: отключить lcp echo и проверять наличие соединения пингами.

Для zyxel keenetic нужно будет зайти по telnet и выполнить набор команд

[code lang="shell"](config)\> interface PPPoE0  
(config-if)\> no lcp echo  
(config-if)\> exit  
(config)\> system config-save  
(config)\> exit[/code]

Тем самым мы отключим проверку, которая обрушивает соединение.

