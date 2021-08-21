---
layout: post
title: 'Sed: полезные советы'
type: post
status: publish
categories:
- HowTo
tags:
- shell
- linux
- обработка текста
permalink: "/2021/08/21/sed_полезные_советы"
---

Всем известен потоковый редактор sed.

Как мы его используем чаще всего? Взять регулярку и заменить что-то на что-то глобально.

```shell
$ seq 1 10 | sed 's/1/one/'
one
2
3
4
5
6
7
8
9
one0
```

Только этим можно не ограничиваться так как это очень мощная штука с условными переходами и промежуточным буфером хранения.

Как работает типичный флоу:

```
 ____      -----------------------      ----
|file| -> |прочесть строку в буфер| -> |line|
 ----     |    (pattern space)    |     ----
    ^     -----------------------
    |                 \/
    |      -----------------------      -------------
    |     |  Выполнить команду 1  | -> |modified line|
    |      -----------------------      -------------
    |                 \/
    |                ....
    |                 \/
    |      -----------------------      -------------
    |     |  Выполнить команду N  | -> |modified line|
    |      -----------------------     | N-iteration |
    |                 \/                -------------
    |      -----------------------      -------------
    |     |   Записать результат  | -> |modified line|
    |      -----------------------     | N-iteration |
    |                 ||                -------------
    |                 \/
    |    --------------------------
    ----| повторить до конца файла |
         --------------------------
```

Под записью резльтата подразумевается не только вывод, но и запись в файл при in-place редактирвоании.

Стоп! А почему команд больше одной? Мы же с вам знаем только одно регулярное выражение?

<!--more-->

Просто - sed умеет выполнять много команд и они разделаются точкой с запятой.

```shell
$ seq 1 10 | sed 's/1/one/;s/2/two/'
one
two
3
4
5
6
7
8
9
one0
```

Редактор так же умеет работать с двумя пространствами:
* текущий буфер в котором идет работа (pattern space);
* временный буфер в который можно сохранять данные и обменивать его с основным (hold space).

Как тут не вспомнить ```glutSwapBuffers()``` и методы вывода графики чтобы не мерцало.

sed дает нам возможность работать с промежуточным буфером читая в него и обменивая с текущим.

* **D** - удалить содержимое основного буфера и перейти на начало цикла;
* **G** - добавить содержимое промежуточного буфера к текущему с ```\n``` в начале;
* **H** - добавить содержимое текущего буфера к промежуточному с ```\n``` в начале;
* **N** - добавить следующую строку к содержимому текущего буфера;
* **P** - печать из текущего буфера до первого символа новой строки;

Существуют аналогичные команды в нижнем регистре, которые работаю игнорируя новую строку.

Пример.

```shell
% seq 1 10 | sed -n 'H;$!d;x;l;P'
\n1\n2\n3\n4\n5\n6\n7\n8\n9\n10$

$ seq 1 10 | sed -n 'H;$!d;x;l;p'
\n1\n2\n3\n4\n5\n6\n7\n8\n9\n10$

1
2
3
4
5
6
7
8
9
10
```

Команда:
* Сохраняет очередную строку в hold-buffer;
* Если не конец файла, то переходит к началу цикла очистив pattern-buffer;
* Меняет hold и pattern buffer;
* Выводит содержимое pattern buffer. И тут у нас различия между p и P явно видны - одна из них останавливается после первого ```\n```. 

Дополнительная команда.
* **l** - вывести содержимое текущего буфера (весь) заэкранировав окончания строк.

Мы имеем дело с последовательностью. Поэтому не может быть каких-то вложенных команд.

Каждая команда может сопровождаться предусловием:
* ```$!``` - не конец файла

  ```shell
  $ seq 1 3 | sed -n '$!p'
  1
  2
  ```
* ```$``` - конец файла

  ```shell
  $ seq 1 3 | sed -n '$p'
  3
  ```
  
Зачем столько команд? Как пример - мультистроковая обработка файла. Проблема мультистрочкой обработки в том, что считывание в рабочий буфер осуществляется построчно.

```shell
$ seq 1 10 | sed -n '{H;$!d};x;s/^\n//;s/\n/:/g;p'
1:2:3:4:5:6:7:8:9:10
```

* ```H``` - добавим строку из рабочего буфера в hold buffer;
* ```$!d``` - пока не наступит конец файла чистим рабочий буфер и переходим в начало;
* ```x``` - загружаем hold buffer в рабочий;
* ```s/^\n//``` - удалим перенос строки в начала (особенность работы ```H```);
* ```s/\n/:/g``` - ```\n -> :```.

**Ветвление.**

Бывает условным и безусловным.

* ```sed ':label command; t label'```

  срабытывает когда предыдущая команда модифицируют текущий буфер;
* ```sed ':label command; b label'```

  срабатывает всегда.

**Безусловное**.

```shell
echo 'abaaaaabababaaaaabbbb' | sed -n 's/aab/ab/; b end p; :end; p'
abaaaabababaaaaabbbb
```

Видим, что вывод результата произошел лишь один раз.

**Условное**.

```shell
echo 'abaaaaabababaaaaabbbb' | sed -n 's/abc/ab/; t end; p; :end; p'
abaaaaabababaaaaabbbb
abaaaaabababaaaaabbbb
```

Вывод командой p был сделан два раза.

На базе ветвлений можно сделать циклы.

Пример.

```shell
$ echo 'abaaaaabababaaaaabbbb' | sed -n ':repeat s/ab/ba/; t repeat;p'
bbbbbbbbaaaaaaaaaaaaa
```

* На каждой итерации осуществляем замену ```ab -> ba```;
* Если замена прошла успешно, то переходим на метку repeat;
* Иначе выводим содержимое буфера.

Посмотрим что проиходит подробнее.

```shell
$ echo 'abaaaaabababaaaaabbbb' | sed -n ':repeat s/ba/ab/; p; t repeat;p'
aabaaaabababaaaaabbbb
aaabaaabababaaaaabbbb
aaaabaabababaaaaabbbb
aaaaababababaaaaabbbb
aaaaaabbababaaaaabbbb
aaaaaababbabaaaaabbbb
aaaaaaabbbabaaaaabbbb
aaaaaaabbabbaaaaabbbb
aaaaaaababbbaaaaabbbb
aaaaaaaabbbbaaaaabbbb
aaaaaaaabbbabaaaabbbb
aaaaaaaabbabbaaaabbbb
aaaaaaaababbbaaaabbbb
aaaaaaaaabbbbaaaabbbb
aaaaaaaaabbbabaaabbbb
aaaaaaaaabbabbaaabbbb
aaaaaaaaababbbaaabbbb
aaaaaaaaaabbbbaaabbbb
aaaaaaaaaabbbabaabbbb
aaaaaaaaaabbabbaabbbb
aaaaaaaaaababbbaabbbb
aaaaaaaaaaabbbbaabbbb
aaaaaaaaaaabbbababbbb
aaaaaaaaaaabbabbabbbb
aaaaaaaaaaababbbabbbb
aaaaaaaaaaaabbbbabbbb
aaaaaaaaaaaabbbabbbbb
aaaaaaaaaaaabbabbbbbb
aaaaaaaaaaaababbbbbbb
aaaaaaaaaaaaabbbbbbbb
aaaaaaaaaaaaabbbbbbbb
aaaaaaaaaaaaabbbbbbbb
```

**Вредный совет**

Как заменить пустую строку в начале файла?

```shell
sed -e '
   # ваш флоу по обработке мультилайна
   y/\n_/_\n/     ;# Обмен новой строки с подчеркиванием
   s/^[^_]*_//    ;# удаляем первое подчеркивание
   y/\n_/_\n/     ;# обращаем замену
' 
```

**Литература:**
* [Sed and Awk 101 Hacks](https://vds-admin.ru/sed-and-awk-101-hacks)
* [GNU sed](https://www.gnu.org/software/sed/manual/html_node/advanced-sed.html#advanced-sed)
* [Portable way to remove the first line from the pattern space (when multiple lines are present)](https://unix.stackexchange.com/questions/468002/portable-way-to-remove-the-first-line-from-the-pattern-space-when-multiple-line/468288)