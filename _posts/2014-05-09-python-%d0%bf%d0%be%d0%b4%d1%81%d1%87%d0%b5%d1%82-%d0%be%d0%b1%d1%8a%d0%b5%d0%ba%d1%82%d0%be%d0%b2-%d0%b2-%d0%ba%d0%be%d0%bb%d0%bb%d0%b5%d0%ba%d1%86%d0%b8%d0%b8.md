---
layout: post
title: 'Python: Подсчет уникальных объектов в коллекции'
date: 2014-05-09 15:34:13.000000000 +04:00
type: post
parent_id: '0'
published: true
password: ''
status: publish
categories:
- HowTo
tags:
- python
meta:
  _publicize_pending: '1'
  _edit_last: '13696577'
  original_post_id: '177'
  _wp_old_slug: '177'
  _wpcom_is_markdown: '1'
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2014/05/09/python-%d0%bf%d0%be%d0%b4%d1%81%d1%87%d0%b5%d1%82-%d0%be%d0%b1%d1%8a%d0%b5%d0%ba%d1%82%d0%be%d0%b2-%d0%b2-%d0%ba%d0%be%d0%bb%d0%bb%d0%b5%d0%ba%d1%86%d0%b8%d0%b8/"
---
```
>>> from collections import Counter >>> Counter('aaabbb') Counter({'a': 3, 'b': 3})
```
