---
layout: post
title: Unicode characters to html entities
date: 2014-05-09 19:27:25.000000000 +04:00
type: post
categories:
- Разработка
tags:
- json
- php
permalink: "/2014/05/09/unicode-characters-to-html-entities/"
---
```javascript
function unicode_escape_sequences($str){  
 $working = json_encode($str);  
 $working = preg_replace('/\u([0-9a-z]{4})/', '&#x$1;', $working);  
 return json_decode($working);  
}
```

