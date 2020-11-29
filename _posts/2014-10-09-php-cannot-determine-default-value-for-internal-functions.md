---
layout: post
title: 'PHP: Cannot determine default value for internal functions'
date: 2014-10-09 13:43:26.000000000 +04:00
type: post
parent_id: '0'
published: true
password: ''
status: publish
categories:
- JFF
tags:
- php
meta:
  _wpcom_is_markdown: '1'
  sharing_disabled: '1'
  _wpas_skip_facebook: '1'
  _wpas_skip_google_plus: '1'
  _wpas_skip_twitter: '1'
  _wpas_skip_linkedin: '1'
  _wpas_skip_tumblr: '1'
  _wpas_skip_path: '1'
  _publicize_pending: '1'
  _rest_api_published: '1'
  _rest_api_client_id: "-1"
  _edit_last: '13696577'
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2014/10/09/php-cannot-determine-default-value-for-internal-functions/"
---
Зашла речь про небезызвестный htmlspecialchars, но не просто у нем, а о нем и о работе с htmlentities. Если передать в этот метод строку с этими энтитями, то получим двойное перекодирование.

[code lang="php"]echo htmlspecialchars('&quot;текст&quot;');[/code]

```
&amp;quot;текст&amp;quot;
```

Именно для того, чтобы избежать подобного был добавлен параметр double\_encode, который по дефолту всегда true.

Но речь не об этом.

Речь о том, что до этого параметра в спецификации стоит еще два других с дефолтными значениями.

На ум приходит использовать рефлексию для получения дефолтных значений и передачи их в функцию. Но не все так просто.

Сначала напишем код, который будет вызывать функции принимая в качестве параметра ассоциативный массив с онными.

[code lang="php"]function call\_user\_func\_params(callable $func, array $params = array()) {  
 if (!is\_callable($func)) {  
 throw "func is not callable";  
 }  
 $reflection = new ReflectionFunction($func);  
 $funcParams = array();

foreach ($reflection-\>getParameters() as $parameter) {  
 $name = $parameter-\>getName();

if (\_\_DEBUG\_\_) {  
 var\_dump($parameter-\>getName());  
 var\_dump($parameter-\>isOptional());  
 }

if (array\_key\_exists($name, $params)) {  
 array\_push($funcParams, $params[$name]);  
 } elseif ($parameter-\>isOptional()) {  
 array\_push($funcParams, $parameter-\>getDefaultValue());  
 } else {  
 throw new Exception("Value for parameter {$name} not found");  
 }  
 }  
 return call\_user\_func\_array($func, $funcParams);  
}[/code]

А теперь воспользуемся ей.

[code lang="php"]function foo($bar = 'bar', $baz = 'baz') {  
 echo "{$bar}, {$baz}\n";  
}

call\_user\_func\_params('foo', array('baz' =\> 'xyz'));[/code]

Выводит то, что и ожидалось.

```
bar, xyz
```

А теперь так.

[code lang="php"]call\_user\_func\_params('htmlspecialchars', array('string' =\> "&quot;текст&quot;", 'double\_encode' =\> false));[/code]

Облом-с.

```
PHP Fatal error: Uncaught exception 'ReflectionException' with message 'Cannot determine default value for internal functions' in file.php
```

А все потому, что пхп обрабатывает встроенные функции иначе, нежели написанные кривыми руками программиста. :)

А еще документация нас открыто уведомляет о том, что именя параметров реальные (я про встроенные функции) могут отличаться от того, что написано в спецификации.

