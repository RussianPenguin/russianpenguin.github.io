---
layout: post
title: 'RxJS: oneInTime'
date: 2013-11-29 18:41:48.000000000 +04:00
type: post
parent_id: '0'
published: true
password: ''
status: publish
categories:
- Разработка
- HowTo
tags:
- javascript
meta:
  _publicize_pending: '1'
  _edit_last: '13696577'
  original_post_id: '75'
  _wp_old_slug: '75'
  _wpcom_is_markdown: '1'
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2013/11/29/rxjs-oneintime/"
---
```javascript; gutter: true; first-line: 1; highlight: []
Rx.Observable.prototype.oneInTime = function (delay) { return this .take(1) .merge(Rx.Observable.empty().delay(delay)) .repeat(); };
```

необходимые модули:

```
rx.js rx.binding.js rx.time.js
```
