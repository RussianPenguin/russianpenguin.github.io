---
layout: post
title: JavaScript и области видимости
type: post
categories:
- Разработка
tags:
- javascript
permalink: "/2015/04/29/javascript-%d0%b8-%d0%be%d0%b1%d0%bb%d0%b0%d1%81%d1%82%d0%b8-%d0%b2%d0%b8%d0%b4%d0%b8%d0%bc%d0%be%d1%81%d1%82%d0%b8/"
---
Об этой особенности полезно иногда вспоминать.

```javascript
// глобальная переменная, которую мы попытаемся дальше получить  
var bar = 42;

// Мозг предполагает, что до объявления bar в теле функциии глобальный bar будет доступен  
function simple_define() {  
 alert(bar);  
 var bar = 10;  
 alert(bar);  
}

// Но это не так. Функция выше на самом деле выглядит вот так  
function real_define() {  
 var bar;  
 alert(bar);  
 bar = 10;  
 alert(bar);  
}

// А что же с условиями? Разве они не создают локальные области видимости?  
function define_inside_if() {  
 if (true) {  
 var bar = -10;  
 }

alert(bar);  
}

// Ну а циклы?  
function define_inside_while() {  
 do {  
 var bar = 10;  
 } while (false);  
 alert(bar);  
}

// И переменные из for?  
function define_inside_for() {  
 for (var bar = 0; bar < 10; bar++);  
 alert(bar);  
}

/*  
 * Да. Это особенность js - компилятор собирает все объявления переменных текущей области видимости  
 * и выделяет под них все требуемые ресурсы сразу при входе в функцию.  
 */
```

