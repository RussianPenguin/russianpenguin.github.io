---
layout: post
title: 'Ajax и заголовок location: особенности'
date: 2015-02-07 23:58:10.000000000 +03:00
type: post
categories:
- Разработка
tags:
- ajax
- javascript
- php
permalink: "/2015/02/07/ajax-%d0%b8-%d0%b7%d0%b0%d0%b3%d0%be%d0%bb%d0%be%d0%b2%d0%be%d0%ba-location-%d0%be%d1%81%d0%be%d0%b1%d0%b5%d0%bd%d0%bd%d0%be%d1%81%d1%82%d0%b8/"
---
Все как обычно: в коде ajax-приложения нужно обрабатывать хидер location, который отдает сервер в некоторых случаях.

Это может потребоваться где угодно. И сейчас для нас важен результат (чтобы оно работало).

Как мы все прекрасно знаем хидеры в jquery можно получить через метод getResponseHeader объекта jqXHR (для других библиотек и голого js можно заглянуть в мануалы).

Казалось бы:

```javascript
$.get('some_url', function(data, status, request) {  
console.log(request.getResponseHeader('location'))  
})
```

Но не все так просто.

Если мы отдаем хидер стандартно для php.

```php
header('location: to_url');
```

То видим. А что видим? А видим, что браузер взял на себя переход по редиректу. Почему? Потому что ответы с кодом 301 и 302 прозрачно обрабатываются самим браузером и в ответе придет уже конечная страница.

Но как только ответ сервера будет 200, так сразу все становится хорошо. Этот код заставляет браузеры забыть об обработке хидера location и позволяет прочитать его на стороне js.

```php
header('location: to_url', true, 200);
```

В [примере](https://github.com/RussianPenguin/blogSamples/blob/master/location.php "Дружим location и js: пример к статье") можно наглядно посмотреть, как происходит обработка. Достаточно его запустить внутри встроенного сервера php.

[![Дружим location и js]({{ site.baseurl }}/assets/images/2015/02/d0b2d18bd0b4d0b5d0bbd0b5d0bdd0b8d0b5_171.png)](/2015/02/d0b2d18bd0b4d0b5d0bbd0b5d0bdd0b8d0b5_171.png)

