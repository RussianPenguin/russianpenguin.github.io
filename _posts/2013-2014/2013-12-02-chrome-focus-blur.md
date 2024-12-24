---
layout: post
title: 'Chrome: события focus и blur'
type: post
categories:
- Разработка
tags:
- javascript
permalink: "/2013/12/02/chrome-%d1%81%d0%be%d0%b1%d1%8b%d1%82%d0%b8%d1%8f-focus-%d0%b8-blur/"
---
У движка webkit есть особенность по-умолчанию инпуты и якоря не получают события focus и blur если у них не установлено свойство tabindex.

Для того, чтобы элементы получали событие focus/blur им нужно присвоить tabindex.

```html; gutter: true; first-line: 1; highlight: []
<a tabindex="1" href="#">Жмякни</a>
```

Это не баг. Это особенность движка webkit.

