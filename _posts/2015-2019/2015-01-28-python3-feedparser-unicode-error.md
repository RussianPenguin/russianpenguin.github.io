---
layout: post
title: 'Python3: feedparser unicode error'
type: post
categories:
- Разработка
tags:
- python
permalink: "/2015/01/28/python3-feedparser-unicode-error/"
---
Есть расширение [universal feed parser](http://pythonhosted.org/feedparser/ "Universal feed parser") и есть у него одна очень неприятная [бага](https://code.google.com/p/feedparser/issues/detail?id=403 "Python feedparser: Issue 403"): если установлено расширение chardet, то парсинг лент в юникоде ломается.

```
$ ./rss.py                                                                                                                                                                     Traceback (most recent call last):  
File "./rss.py", line 11, in <module>  
feed = feedparser.parse(rss_response.text)  
File "/usr/lib/python3.3/site-packages/feedparser.py", line 3966, in parse  
data, result['encoding'], error = convert_to_utf8(http_headers, data)  
File "/usr/lib/python3.3/site-packages/feedparser.py", line 3768, in convert_to_utf8  
chardet_encoding = str(chardet.detect(data)['encoding'] or '', 'ascii', 'ignore')  
TypeError: decoding str is not supported
```

Неприятно. Можно удалить chardet, но тогда другие расширения, которые от него зависят будут удалены. В том числе и [requests](http://www.python-requests.org/en/latest/ "requests").

Значит надо чинить. Но поскольку баг пофикшен только в транке, а текущая стейбл 5.1.3, то надо обновляться из транка.

```shell
$ sudo python3-pip install git+https://code.google.com/p/feedparser/ --upgrade
```

Или накатить [патч](https://code.google.com/p/feedparser/issues/attachmentText?id=403&aid=4030000000&name=feedparser.patch&token=ABZ6GAcpVSaLcr3xuUIcLCLJ2W9HJATMAQ%3A1422388699363 "Feedparser unicode+chardet fix") руками.

