---
layout: post
title: 'gdb: запуск приложения'
date: 2013-11-14 14:06:28.000000000 +04:00
type: post
parent_id: '0'
published: true
password: ''
status: publish
categories:
- HowTo
tags:
- отладка
meta:
  _publicize_pending: '1'
  _edit_last: '13696577'
  original_post_id: '19'
  _wp_old_slug: '19'
  _wpcom_is_markdown: '1'
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2013/11/14/gdb_how_to_run_application/"
---
установка новых аргументов для запуска программы

```
set args <args>
```

просмотр аргументов

```
show args
```

переход по ассемблерным командам

```
ni, si
```

Отобразить следующие n инструкций

```
display/ni $pc
```

Поиск в памяти

```
find
```
