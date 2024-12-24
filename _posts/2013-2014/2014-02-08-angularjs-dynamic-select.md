---
layout: post
title: 'AngularJS: Динамический диапазон для select'
date: 2014-02-08 15:01:22.000000000 +04:00
type: post
categories:
- HowTo
tags:
- angularjs
- javascript
permalink: "/2014/02/08/angularjs-%d0%b4%d0%b8%d0%bd%d0%b0%d0%bc%d0%b8%d1%87%d0%b5%d1%81%d0%ba%d0%b8%d0%b9-%d0%b4%d0%b8%d0%b0%d0%bf%d0%b0%d0%b7%d0%be%d0%bd-%d0%b4%d0%bb%d1%8f-select/"
---
С сервера приходит максимальное и минимальное значение в выпадающем списке. Нам нужно построить по этим значениям сам список.

Делаем фильтр

```php
angular.module('app').filter('range', function() {  
 return function(input, min, max) {  
 min = parseInt(min, 10);  
 max = parseInt(max, 10);  
 for (var i = min; i < max; i++)  
 input.push(i);  
 return input;  
 };  
});
```

И делаем динамический селект.

```html
<select ng-model="value" ng-options="item for item in [] | range:min:max"></select>
```

[JSFiddle](http://jsfiddle.net/russianpenguin/bYUFb "Пример на JSFiddle")

