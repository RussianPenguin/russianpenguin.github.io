---
layout: post
title: 'PHP+Apache: глюк?'
date: 2015-11-30 20:18:29.000000000 +03:00
type: post
parent_id: '0'
published: true
password: ''
status: publish
categories:
- Разработка
tags:
- php
meta:
  _wpcom_is_markdown: '1'
  _rest_api_published: '1'
  _rest_api_client_id: "-1"
  _publicize_job_id: '17339957648'
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2015/11/30/phpapache-%d0%b3%d0%bb%d1%8e%d0%ba/"
---
Сегодня столкнулся с совершенно с чудовищным по своей странности багом.

Есть код. Простейший.

```php
$a = array('' =\> 'value');

$key = '';  
$falseKey = false;  
$falseKey = (string)$falseKey; // $falseKey === '' будет true

var\_dump(isset($a[$key]));  
var\_dump(isset($a[$falseKey]));
```

Вы думаете, что в обоих случаях код выведет true?  
А вот и нет.

Существуют какие-то глюки в связке модуля пхп и апача, которые приводят к тому, что во втором случае код выдаст false.

Это не вылечилось перезагрузкой апача. Вылечилось лишь его полной остановкой и запуском.

Любопытно, что данный баг воспроизвелся лишь на одном сервере. На других абсолютно идентичных он не воспроизводился.

**UPD (13.12.2015):**  
Таки "автором" этого глюка выступило расширение xdebug. К сожалению детального разбора проблемы я не осуществлял. Просто если вы встретились с неверным пониманием языком типов переменных, то смотрите в сторону xdebug.

