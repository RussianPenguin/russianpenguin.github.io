---
layout: post
title: Unicode characters to html entities
date: 2014-05-09 19:27:25.000000000 +04:00
type: post
parent_id: '0'
published: true
password: ''
status: publish
categories:
- Разработка
tags:
- json
- php
meta:
  _publicize_pending: '1'
  _edit_last: '13696577'
  original_post_id: '195'
  _wp_old_slug: '195'
  geo_public: '0'
  _wpcom_is_markdown: '1'
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2014/05/09/unicode-characters-to-html-entities/"
---
```javascript
function unicode\_escape\_sequences($str){  
 $working = json\_encode($str);  
 $working = preg\_replace('/\u([0-9a-z]{4})/', '&#x$1;', $working);  
 return json\_decode($working);  
}
```

