---
layout: post
title: 'PHP: Cannot determine default value for internal functions'
type: post
categories:
- JFF
tags:
- php
permalink: "/2014/10/09/php-cannot-determine-default-value-for-internal-functions/"
---
Зашла речь про небезызвестный htmlspecialchars, но не просто у нем, а о нем и о работе с htmlentities. Если передать в этот метод строку с этими энтитями, то получим двойное перекодирование.

```php
echo htmlspecialchars('&quot;текст&quot;');
```

```
&amp;quot;текст&amp;quot;
```

Именно для того, чтобы избежать подобного был добавлен параметр double_encode, который по дефолту всегда true.

Но речь не об этом.

Речь о том, что до этого параметра в спецификации стоит еще два других с дефолтными значениями.

На ум приходит использовать рефлексию для получения дефолтных значений и передачи их в функцию. Но не все так просто.

Сначала напишем код, который будет вызывать функции принимая в качестве параметра ассоциативный массив с онными.

```php
function call_user_func_params(callable $func, array $params = array()) {  
 if (!is_callable($func)) {  
 throw "func is not callable";  
 }  
 $reflection = new ReflectionFunction($func);  
 $funcParams = array();

foreach ($reflection->getParameters() as $parameter) {  
 $name = $parameter->getName();

if (__DEBUG__) {  
 var_dump($parameter->getName());  
 var_dump($parameter->isOptional());  
 }

if (array_key_exists($name, $params)) {  
 array_push($funcParams, $params[$name]);  
 } elseif ($parameter->isOptional()) {  
 array_push($funcParams, $parameter->getDefaultValue());  
 } else {  
 throw new Exception("Value for parameter {$name} not found");  
 }  
 }  
 return call_user_func_array($func, $funcParams);  
}
```

А теперь воспользуемся ей.

```php
function foo($bar = 'bar', $baz = 'baz') {  
 echo "{$bar}, {$baz}\n";  
}

call_user_func_params('foo', array('baz' => 'xyz'));
```

Выводит то, что и ожидалось.

```
bar, xyz
```

А теперь так.

```php
call_user_func_params('htmlspecialchars', array('string' => "&quot;текст&quot;", 'double_encode' => false));
```

Облом-с.

```
PHP Fatal error: Uncaught exception 'ReflectionException' with message 'Cannot determine default value for internal functions' in file.php
```

А все потому, что пхп обрабатывает встроенные функции иначе, нежели написанные кривыми руками программиста. :)

А еще документация нас открыто уведомляет о том, что именя параметров реальные (я про встроенные функции) могут отличаться от того, что написано в спецификации.

