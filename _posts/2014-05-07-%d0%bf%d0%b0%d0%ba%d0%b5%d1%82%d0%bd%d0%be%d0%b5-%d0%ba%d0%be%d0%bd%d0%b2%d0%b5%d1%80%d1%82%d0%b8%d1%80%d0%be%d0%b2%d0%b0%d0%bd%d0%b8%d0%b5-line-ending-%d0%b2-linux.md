---
layout: post
title: Пакетное конвертирование line-ending в linux
date: 2014-05-07 14:35:45.000000000 +04:00
type: post
categories:
- HowTo
tags:
- linux
- shell
permalink: "/2014/05/07/%d0%bf%d0%b0%d0%ba%d0%b5%d1%82%d0%bd%d0%be%d0%b5-%d0%ba%d0%be%d0%bd%d0%b2%d0%b5%d1%80%d1%82%d0%b8%d1%80%d0%be%d0%b2%d0%b0%d0%bd%d0%b8%d0%b5-line-ending-%d0%b2-linux/"
---
```
find . -name "*.css" -exec vi +':w ++ff=unix' +':q' {} ;
```
