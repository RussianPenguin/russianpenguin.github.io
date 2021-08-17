---
layout: post
title: 'Python: Backreferences в re'
date: 2013-12-24 23:29:08.000000000 +04:00
type: post
parent_id: '0'
published: true
password: ''
status: publish
categories:
- HowTo
tags:
- python
- re
meta:
  _publicize_pending: '1'
  _edit_last: '13696577'
  original_post_id: '127'
  _wp_old_slug: '127'
  _wpcom_is_markdown: '1'
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2013/12/24/python-backreferences-%d0%b2-re/"
---
Вот все время забываю, что обратные ссылки в регулярных выражениях надо либо писать экранируя слеш.

```python; gutter: true; first-line: 1; highlight: []
>>> import re >>> re.sub('(d)\1*', '\1', '111112222233333') '123'
```

Либо писать в raw-строках

```python; gutter: true; first-line: 1; highlight: []
>>> import re >>> re.sub(r'(d)1*', r'1', '111112222233333') '123'
```
