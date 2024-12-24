---
layout: post
title: 'JS: аналог str_repeat'
date: 2014-04-03 20:43:18.000000000 +04:00
type: post
categories:
- Разработка
- HowTo
tags:
- javascript
- php
permalink: "/2014/04/03/js-%d0%b0%d0%bd%d0%b0%d0%bb%d0%be%d0%b3-str_repeat/"
---
```javascript
function str_repeat($string, $multiplier) {
  return new Array($multiplier+1).join($string)
}
```
