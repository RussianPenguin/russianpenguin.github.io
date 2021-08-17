---
layout: post
title: 'Chrome: события focus и blur'
date: 2013-12-02 18:49:42.000000000 +04:00
type: post
parent_id: '0'
published: true
password: ''
status: publish
categories:
- Разработка
tags:
- javascript
meta:
  _publicize_pending: '1'
  _edit_last: '13696577'
  original_post_id: '109'
  _wp_old_slug: '109'
  _wpcom_is_markdown: '1'
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2013/12/02/chrome-%d1%81%d0%be%d0%b1%d1%8b%d1%82%d0%b8%d1%8f-focus-%d0%b8-blur/"
---
У движка webkit есть особенность по-умолчанию инпуты и якоря не получают события focus и blur если у них не установлено свойство tabindex.

Для того, чтобы элементы получали событие focus/blur им нужно присвоить tabindex.

```html; gutter: true; first-line: 1; highlight: []
<a tabindex="1" href="#">Жмякни</a>
```

Это не баг. Это особенность движка webkit.

