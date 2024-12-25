---
layout: post
title: 'Python: Backreferences в re'
type: post
categories:
- HowTo
tags:
- python
- re
permalink: "/2013/12/24/python-backreferences-%d0%b2-re/"
---
Вот все время забываю, что обратные ссылки в регулярных выражениях надо либо писать экранируя слеш.

```python
>>> import re
>>> re.sub('(d)\1*', '\1', '111112222233333')
'123'
```

Либо писать в raw-строках

```python
>>> import re
>>> re.sub(r'(d)1*', r'1', '111112222233333')
'123'
```
