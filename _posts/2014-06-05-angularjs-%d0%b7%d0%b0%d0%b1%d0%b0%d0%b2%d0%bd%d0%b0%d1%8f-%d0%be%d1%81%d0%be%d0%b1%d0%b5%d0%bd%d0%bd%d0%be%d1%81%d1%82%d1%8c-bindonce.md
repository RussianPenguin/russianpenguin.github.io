---
layout: post
title: 'AngularJS: забавная особенность bindonce'
date: 2014-06-05 15:53:01.000000000 +04:00
type: post
categories:
- JFF
tags:
- angularjs
- javascript
permalink: "/2014/06/05/angularjs-%d0%b7%d0%b0%d0%b1%d0%b0%d0%b2%d0%bd%d0%b0%d1%8f-%d0%be%d1%81%d0%be%d0%b1%d0%b5%d0%bd%d0%bd%d0%be%d1%81%d1%82%d1%8c-bindonce/"
---
Для AngularJS существует модуль [bindonce](https://github.com/Pasvaz/bindonce "Pasvaz/bindonce"), который позволяет сократить количество вотчеров и тем самым ускорить страинцу.

У этого модуля есть директива bo-attr, которая позволяет использовать в качестве атрибута элемента любое нужное нам значение. В качестве значения выступает выражение, которое будет проинтерпретировано и добавлено в dom.

Однако, у этой директивы есть забавное поведение, которое связано с особенностями интерпретации.

```javascript
$scope.title = 'some text with $peci@l chars'  
$scope.title_ref = 'title'  
$scope.title_title_ref = 'title_ref'
```

```html
<a bo-attr="" bo-attr-title="title">anchor1</a>  
<a bo-attr="" bo-attr-title="{{title_ref}}">anchor2</a>  
<a bo-attr="" bo-attr-title="'{{title}}'">anchor3</a>  
<a bo-attr="" bo-attr-title="{{title_title_ref}}">anchor4</a>
```

Как думаете, что выведется в каждом случае? :)

[Фидл с примером](http://jsfiddle.net/russianpenguin/53MeW/ "Интересное поведение директивы bo-attr").

