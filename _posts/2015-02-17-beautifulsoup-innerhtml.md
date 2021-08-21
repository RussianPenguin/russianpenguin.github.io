---
layout: post
title: BeautifulSoup innerHTML
date: 2015-02-17 21:54:08.000000000 +03:00
type: post
categories:
- Обработка текста
- Разработка
tags:
- python
permalink: "/2015/02/17/beautifulsoup-innerhtml/"
---
Функция в рамках браузеров по сей день остается недокументированной. А значит на стороне большинства парсеров она не реализована.

Но как быть если хочется?

```python
def innerHTML(element):  
 return element.decode_contents(formatter="html")
```

