---
layout: post
title: 'PostgreSQL: insert on duplicate key'
date: 2014-08-19 15:24:36.000000000 +04:00
type: post
parent_id: '0'
published: true
password: ''
status: publish
categories:
- Найдено в сети
- Разработка
tags:
- sql
meta:
  _wpcom_is_markdown: '1'
  sharing_disabled: '1'
  _wpas_skip_facebook: '1'
  _wpas_skip_google_plus: '1'
  _wpas_skip_twitter: '1'
  _wpas_skip_linkedin: '1'
  _wpas_skip_tumblr: '1'
  _wpas_skip_path: '1'
  _edit_last: '13696577'
  geo_public: '0'
  _publicize_pending: '1'
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2014/08/19/postgresql-insert-on-duplicate-key/"
---
Да-да. Постгрес не умеет делать

[code lang="sql"]insert \* on duplicate key ...[/code]

Но это легко&nbsp;[эмулируется](http://stackoverflow.com/a/6527838/1216190) последовательностью запросов.

[code lang="sql"]UPDATE table SET field='C', field2='Z' WHERE id=3;  
INSERT INTO table (id, field, field2)  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; SELECT 3, 'C', 'Z'  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; WHERE NOT EXISTS (SELECT 1 FROM table WHERE id=3);[/code]

