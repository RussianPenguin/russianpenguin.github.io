---
layout: post
title: Подсчет количества вхождений регэкспа в файле
date: 2013-11-27 23:50:10.000000000 +04:00
type: post
categories:
- HowTo
tags:
- консоль
permalink: "/2013/11/27/%d0%ba%d0%be%d0%bb%d0%b8%d1%87%d0%b5%d1%81%d1%82%d0%b2%d0%be-%d0%b2%d1%85%d0%be%d0%b6%d0%b4%d0%b5%d0%bd%d0%b8%d0%b9/"
---
```shell
$ grep -o -e 'S' text.txt |wc -l 513274
```
