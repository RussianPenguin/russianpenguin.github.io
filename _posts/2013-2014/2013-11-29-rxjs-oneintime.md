---
layout: post
title: 'RxJS: oneInTime'
date: 2013-11-29 18:41:48.000000000 +04:00
type: post
categories:
- Разработка
- HowTo
tags:
- javascript
permalink: "/2013/11/29/rxjs-oneintime/"
---
```javascript; gutter: true; first-line: 1; highlight: []
Rx.Observable.prototype.oneInTime = function (delay) { return this .take(1) .merge(Rx.Observable.empty().delay(delay)) .repeat(); };
```

необходимые модули:

```
rx.js rx.binding.js rx.time.js
```
