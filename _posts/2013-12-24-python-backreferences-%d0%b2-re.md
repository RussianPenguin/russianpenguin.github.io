---
layout: post
title: 'Python: Backreferences в re'
date: 2013-12-24 23:29:08.000000000 +04:00
type: post
categories:
- HowTo
tags:
- python
- re
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
