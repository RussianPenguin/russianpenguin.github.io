---
layout: post
title: 'JavaScript: копирование объектов'
date: 2013-11-13 15:26:42.000000000 +04:00
type: post
parent_id: '0'
published: true
password: ''
status: publish
categories:
- HowTo
tags:
- jsvascript
meta:
  _publicize_pending: '1'
  _edit_last: '13696577'
  original_post_id: '13'
  _wp_old_slug: '13'
  _wpcom_is_markdown: '1'
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2013/11/13/javascript_copy_objects/"
---
Иногда очень надо копировать объект в js, а не ссылку на него.

```
var newObject = jQuery.extend(true, {}, oldObject);
```

или

```
var newObject = JSON.parse(JSON.stringify(oldObject))
```
