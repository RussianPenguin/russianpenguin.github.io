---
layout: post
title: 'AngularJS: забавная особенность bindonce'
date: 2014-06-05 15:53:01.000000000 +04:00
type: post
parent_id: '0'
published: true
password: ''
status: publish
categories:
- JFF
tags:
- angularjs
- javascript
meta:
  _publicize_pending: '1'
  _edit_last: '13696577'
  original_post_id: '212'
  _wp_old_slug: '212'
  geo_public: '0'
  _wpcom_is_markdown: '1'
  _oembed_32b3f05d99161a76a9431cfdebbd7234: "{{unknown}}"
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2014/06/05/angularjs-%d0%b7%d0%b0%d0%b1%d0%b0%d0%b2%d0%bd%d0%b0%d1%8f-%d0%be%d1%81%d0%be%d0%b1%d0%b5%d0%bd%d0%bd%d0%be%d1%81%d1%82%d1%8c-bindonce/"
---
Для AngularJS существует модуль [bindonce](https://github.com/Pasvaz/bindonce "Pasvaz/bindonce"), который позволяет сократить количество вотчеров и тем самым ускорить страинцу.

У этого модуля есть директива bo-attr, которая позволяет использовать в качестве атрибута элемента любое нужное нам значение. В качестве значения выступает выражение, которое будет проинтерпретировано и добавлено в dom.

Однако, у этой директивы есть забавное поведение, которое связано с особенностями интерпретации.

```javascript
$scope.title = 'some text with $peci@l chars'  
$scope.title\_ref = 'title'  
$scope.title\_title\_ref = 'title\_ref'
```

```html
\<a bo-attr="" bo-attr-title="title"\>anchor1\</a\>  
\<a bo-attr="" bo-attr-title="{{title\_ref}}"\>anchor2\</a\>  
\<a bo-attr="" bo-attr-title="'{{title}}'"\>anchor3\</a\>  
\<a bo-attr="" bo-attr-title="{{title\_title\_ref}}"\>anchor4\</a\>
```

Как думаете, что выведется в каждом случае? :)

[Фидл с примером](http://jsfiddle.net/russianpenguin/53MeW/ "Интересное поведение директивы bo-attr").

