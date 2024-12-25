---
layout: post
title: Python 3+ и окончания строк в файлах
type: post
categories:
- Обработка текста
- Разработка
tags:
- python
permalink: "/2015/02/17/python-3-%d0%b8-%d0%be%d0%ba%d0%be%d0%bd%d1%87%d0%b0%d0%bd%d0%b8%d1%8f-%d1%81%d1%82%d1%80%d0%be%d0%ba-%d0%b2-%d1%84%d0%b0%d0%b9%d0%bb%d0%b0%d1%85/"
---
Столкнулся одного теста, который был перенесен с python 2+ на python 3+.

Тест делал следующее:

скачивал файл через python.requests и сравнивал его с эталонным содержимым на диске (посимвольно).

Выглядело приблизительно так

```python
import requests  
import sys  
response = requests.get(sys.argv[1])  
if response.code == 200:  
 with open(sys.argv[2]) as f:  
 from_storage = f.read()  
 from_web = response.text  
 assert from_web == from_storage
```

Да. Все верно. Этот тест не проходил.

И тут была замечена одна странность: файл на диске содержал последовательность crlf, а в coдержимом from_storage этой последовательности не оказало.

А дело все в том, что в python 3+ было введено [соглашение](https://docs.python.org/release/3.2/library/functions.html#open "Python 3+ - open function") на обработку символов перевода строки. И управление работой осуществляется манипулированием параметром newline.

- On input, if newline is None, universal newlines mode is enabled. Lines in the input can end in `\n`, `\r`, or `\r\n`, and these are translated into `\n` before being returned to the caller. If it is '', universal newline mode is enabled, but line endings are returned to the caller untranslated. If it has any of the other legal values, input lines are only terminated by the given string, and the line ending is returned to the caller untranslated.
- On output, if newline is None, any `\n` characters written are translated to the system default line separator, os.linesep. If newline is '', no translation takes place. If newline is any of the other legal values, any '\n' characters written are translated to the given string.

В итоге достаточно было указать newline='' как CRLF появились.

