---
layout: post
title: 'gcc: защита от переполнения стека'
date: 2013-11-14 08:01:25.000000000 +04:00
type: post
parent_id: '0'
published: true
password: ''
status: publish
categories:
- HowTo
tags:
- программирование
meta:
  _publicize_pending: '1'
  _edit_last: '13696577'
  original_post_id: '15'
  _wp_old_slug: '15'
  _wpcom_is_markdown: '1'
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2013/11/14/g%d1%81%d1%81_stack_protection/"
---
Есть необходимость выполнить компиляцию кода в gdb без защиты от переполнения стека

```
-fno-stack-protector
```

и без защиты от испольнения кода в стеке

```
-z execstack
```
