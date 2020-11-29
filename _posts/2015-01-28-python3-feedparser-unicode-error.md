---
layout: post
title: 'Python3: feedparser unicode error'
date: 2015-01-28 00:15:51.000000000 +03:00
type: post
parent_id: '0'
published: true
password: ''
status: publish
categories:
- Разработка
tags:
- python
meta:
  _wpcom_is_markdown: '1'
  sharing_disabled: '1'
  _wpas_skip_facebook: '1'
  _wpas_skip_google_plus: '1'
  _wpas_skip_twitter: '1'
  _wpas_skip_linkedin: '1'
  _wpas_skip_tumblr: '1'
  _wpas_skip_path: '1'
  _publicize_pending: '1'
  _rest_api_published: '1'
  _rest_api_client_id: "-1"
  _edit_last: '13696577'
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2015/01/28/python3-feedparser-unicode-error/"
---
Есть расширение [universal feed parser](http://pythonhosted.org/feedparser/ "Universal feed parser") и есть у него одна очень неприятная [бага](https://code.google.com/p/feedparser/issues/detail?id=403 "Python feedparser: Issue 403"): если установлено расширение chardet, то парсинг лент в юникоде ломается.

```
$ ./rss.py&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Traceback (most recent call last):  
File "./rss.py", line 11, in \<module\>  
feed = feedparser.parse(rss\_response.text)  
File "/usr/lib/python3.3/site-packages/feedparser.py", line 3966, in parse  
data, result['encoding'], error = convert\_to\_utf8(http\_headers, data)  
File "/usr/lib/python3.3/site-packages/feedparser.py", line 3768, in convert\_to\_utf8  
chardet\_encoding = str(chardet.detect(data)['encoding'] or '', 'ascii', 'ignore')  
TypeError: decoding str is not supported
```

Неприятно. Можно удалить chardet, но тогда другие расширения, которые от него зависят будут удалены. В том числе и [requests](http://www.python-requests.org/en/latest/ "requests").

Значит надо чинить. Но поскольку баг пофикшен только в транке, а текущая стейбл 5.1.3, то надо обновляться из транка.

```shell
$ sudo python3-pip install git+https://code.google.com/p/feedparser/ --upgrade
```

Или накатить [патч](https://code.google.com/p/feedparser/issues/attachmentText?id=403&aid=4030000000&name=feedparser.patch&token=ABZ6GAcpVSaLcr3xuUIcLCLJ2W9HJATMAQ%3A1422388699363 "Feedparser unicode+chardet fix") руками.

