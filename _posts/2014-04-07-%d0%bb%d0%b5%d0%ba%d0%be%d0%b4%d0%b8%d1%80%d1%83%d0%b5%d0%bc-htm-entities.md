---
layout: post
title: Р? (Декодируем html-entities при помощи jQuery)
date: 2014-04-07 22:00:00.000000000 +04:00
type: post
parent_id: '0'
published: true
password: ''
status: publish
categories:
- HowTo
tags:
- javascript
meta:
  _publicize_pending: '1'
  _edit_last: '13696577'
  original_post_id: '156'
  _wp_old_slug: '156'
  _wpcom_is_markdown: '1'
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2014/04/07/%d0%bb%d0%b5%d0%ba%d0%be%d0%b4%d0%b8%d1%80%d1%83%d0%b5%d0%bc-htm-entities/"
---
```javascript; gutter: true; first-line: 1; highlight: []
var decoded = $("<div/>").html(encodedStr).text();
```
