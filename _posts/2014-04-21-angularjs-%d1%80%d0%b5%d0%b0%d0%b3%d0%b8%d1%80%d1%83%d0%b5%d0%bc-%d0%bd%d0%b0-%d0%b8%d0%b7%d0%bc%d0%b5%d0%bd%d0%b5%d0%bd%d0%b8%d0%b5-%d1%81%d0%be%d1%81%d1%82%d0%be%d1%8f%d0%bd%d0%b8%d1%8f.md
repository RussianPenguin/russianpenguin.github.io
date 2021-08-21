---
layout: post
title: 'AngularJS: реагируем на изменение состояния объекта'
date: 2014-04-21 23:15:11.000000000 +04:00
type: post
categories:
- Разработка
tags:
- angularjs
- javascript
permalink: "/2014/04/21/angularjs-%d1%80%d0%b5%d0%b0%d0%b3%d0%b8%d1%80%d1%83%d0%b5%d0%bc-%d0%bd%d0%b0-%d0%b8%d0%b7%d0%bc%d0%b5%d0%bd%d0%b5%d0%bd%d0%b8%d0%b5-%d1%81%d0%be%d1%81%d1%82%d0%be%d1%8f%d0%bd%d0%b8%d1%8f/"
---
 ~~Допустим~~ У нас есть часть шаблона, которая может быть либо отображена, либо скрыта. Нам нужно правильно реагировать на это и рассылать оповещения в скоуп (надо так).

Тогда мы просто берем и начинаем следить за состоянием нужного элемента.

```javascript
angular.module('app')directive('watchState', function($rootScope) {  
 return {  
 restrict: 'A',  
 controller: function($scope, $element) {  
 // show. that state was changed by outer source.  
 // prevent action when no changes in $digest cycle  
 var toggled = false

$scope.$watch(function() {  
 if ($element.hasClass('ng-hide')) {  
 if (!toggled) {  
 toggled = true  
 $rootScope.$broadcast('log', 'text is hidden')  
 }  
 } else {  
 if (toggled) {  
 toggled = false  
 $rootScope.$broadcast('log', 'text is visible')  
 }  
 }  
 })  
 }  
 }  
})
```

Теперь мы можем следить за состоянием отображения любого элемента.

```html
<p ng-hide="hidden" watch-state>some text</p>
```

[Пример на jsFiddle](http://jsfiddle.net/russianpenguin/EgRrp/ "Фидл с примером")

