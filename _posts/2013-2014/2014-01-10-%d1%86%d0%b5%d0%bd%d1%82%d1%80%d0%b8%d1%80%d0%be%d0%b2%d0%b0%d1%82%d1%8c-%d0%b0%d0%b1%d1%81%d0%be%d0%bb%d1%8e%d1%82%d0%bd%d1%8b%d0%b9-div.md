---
layout: post
title: Центрировать абсолютный div
date: 2014-01-10 18:27:14.000000000 +04:00
type: post
categories:
- HowTo
tags:
- css
permalink: "/2014/01/10/%d1%86%d0%b5%d0%bd%d1%82%d1%80%d0%b8%d1%80%d0%be%d0%b2%d0%b0%d1%82%d1%8c-%d0%b0%d0%b1%d1%81%d0%be%d0%bb%d1%8e%d1%82%d0%bd%d1%8b%d0%b9-div/"
---
Горизонтально:

```css
margin-left:-<half width>px; left:50%;
```

Вертикально и горизонтально (только если задана ширина и высота)

```css
.Absolute-Center { margin: auto; position: absolute; top: 0; left: 0; bottom: 0; right: 0; }
```
