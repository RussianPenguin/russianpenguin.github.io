---
layout: post
title: 'Концепт: нормируем яркость изображений'
date: 2013-12-04 18:55:56.000000000 +04:00
type: post
categories:
- Обработка изображений
tags:
- обработка изображений
permalink: "/2013/12/04/%d0%bd%d0%be%d1%80%d0%bc%d0%b8%d1%80%d1%83%d0%b5%d0%bc-%d1%8f%d1%80%d0%ba%d0%be%d1%81%d1%82%d1%8c-%d0%b8%d0%b7%d0%be%d0%b1%d1%80%d0%b0%d0%b6%d0%b5%d0%bd%d0%b8%d0%b9/"
---
Задача: в папке лежат изображения. Нужно привести их все приблизительно к одной яркости.

Найдем среднее значение яркости по изображениям в каталоге. Для этого ресайзим изображения до одного пикселя и выщитываем значение яркости по полученному пикселю.

```shell; gutter: true; first-line: 1; highlight: []
convert input.jpg -resize 1x1 txt:- # ImageMagick pixel enumeration: 1,1,255,srgb 0,0: ( 15, 47, 66) #0F2F42 srgb(15,47,66)
```

Для каждого изображения выщитываем его яркость относительно средней и модифицируем ее значение.

```shell; gutter: true; first-line: 1; highlight: []
convert input.jpg -modulate XXX output.jpg
```

Ссылки

[http://stackoverflow.com/questions/7935814/how-to-determine-if-image-is-dark-high-contrast-low-brightness](http://stackoverflow.com/questions/7935814/how-to-determine-if-image-is-dark-high-contrast-low-brightness "Вычисление яркости изображения")

