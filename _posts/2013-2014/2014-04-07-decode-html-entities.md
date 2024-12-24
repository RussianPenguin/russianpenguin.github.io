---
layout: post
title: Р? (Декодируем html-entities при помощи jQuery)
date: 2014-04-07 22:00:00.000000000 +04:00
type: post
categories:
- HowTo
tags:
- javascript
permalink: "/2014/04/07/%d0%bb%d0%b5%d0%ba%d0%be%d0%b4%d0%b8%d1%80%d1%83%d0%b5%d0%bc-html-entities/"
---
```javascript
var decoded = $("<div/>").html(encodedStr).text();
```
