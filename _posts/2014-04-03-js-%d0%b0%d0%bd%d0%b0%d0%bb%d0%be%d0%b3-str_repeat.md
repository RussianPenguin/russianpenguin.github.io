---
layout: post
title: 'JS: аналог str_repeat'
date: 2014-04-03 20:43:18.000000000 +04:00
type: post
parent_id: '0'
published: true
password: ''
status: publish
categories:
- Разработка
- HowTo
tags:
- javascript
- php
meta:
  _publicize_pending: '1'
  _edit_last: '13696577'
  original_post_id: '153'
  _wp_old_slug: '153'
  _wpcom_is_markdown: '1'
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2014/04/03/js-%d0%b0%d0%bd%d0%b0%d0%bb%d0%be%d0%b3-str_repeat/"
---
```javascript; gutter: true; first-line: 1; highlight: []
function str\_repeat($string, $multiplier) { return new Array($multiplier+1).join($string) }
```
