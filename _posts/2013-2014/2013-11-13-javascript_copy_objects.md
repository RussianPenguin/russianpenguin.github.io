---
layout: post
title: 'JavaScript: копирование объектов'
type: post
categories:
- HowTo
tags:
- jsvascript
permalink: "/2013/11/13/javascript_copy_objects/"
---
Иногда очень надо копировать объект в js, а не ссылку на него.

```
var newObject = jQuery.extend(true, {}, oldObject);
```

или

```
var newObject = JSON.parse(JSON.stringify(oldObject))
```
