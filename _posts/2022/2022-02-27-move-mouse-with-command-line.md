---
layout: post
title: 'Xorg: двигаем мышку'
type: post
status: publish
categories:
- HowTo
tags:
- shell
- linux
- bash
- X
permalink: "/2022/02/27/xorg_двигаем-мышку"
---

<img class="kdpv left" src="{{ site.baseurl }}/assets/images/2022/mouse-pointer.png" alt="mouse pointer" title="mouse pointer" />

Это какая-то напасть. Мониторы стаповаяться больше, мониторов становится больше. А курсор мыши так и остался крохотным.

В винде была (может быть есть и сейчас) прикольная функция, которая позволяла подсветить местоположение курсора. Я хотел что-то аналогичное, но потом понял, что имея под руеой три монитора этот курсор потом надо еще и пригнать на нужный монитор, а это огромные затраты энеркии по его перемещению.

Что делать? Сделать хоткей, который будет пригонять курсор в центр конкретного экрана.

```shell
#!/usr/bin/env bash

SCREEN=${1:-0}
GEOMETRY=`xdotool getdisplaygeometry --screen ${SCREEN}`

while read w h
do
    xdotool mousemove --screen $SCREEN $((w/2)) $((h/2))
done < <(echo $GEOMETRY)
```

В качестве аргумента скрипт принимает номер монитора на который хочется пригнать курсор.

Что полезного можно тут увидеть?

Первое - это подстановки. В качестве примера инициализация SCREEN.

|   Выражение        |       parameter      |     parameter   |    parameter    |
|   в скрипте:       | установлен и не Null |установлен и Null|  не установлен  |
|--------------------|----------------------|-----------------|-----------------|
| ${parameter:-word} |  подставит parameter |  подставит word |  подставит word |
| ${parameter-word}  |  подставит parameter |  подставит null |  подставит word |
| ${parameter:=word} |  подставит parameter |   присвоит word | присвоит word   |
| ${parameter=word}  |  подставит parameter |  подставит null | присвоит word   |
| ${parameter:?word} |  подставит parameter |   ошибка, exit  | ошибка, exit    |
| ${parameter?word}  |  подставит parameter |  подставит null | ошибка, exit    |
| ${parameter:+word} |  подставит word      |  подставит null |  подставит null |
| ${parameter+word}  |  подставит word      |  подставит word |  подставит null |

Тут нужно обратить внимание на то, как раскрывается это выражение интерпретатором и что подстановка и присвоение - это разные вещи. 

Подстановка возвращает выбранное значение word вместо переменной parameter. Присвоение же устанавливает parameter значение word.

Пример.

|   Выражение        |       parameter      |     parameter   |    parameter    |
|   в скрипте:       | установлен и не Null |установлен и Null|  не установлен  |
|--------------------|----------------------|-----------------|-----------------|
| ${FOO:-hello}      | world                | hello           | hello           |
| ${FOO-hello}       | world                | ""              | hello           |
| ${FOO:=hello}      | world                | FOO=hello       | FOO=hello       |
| ${FOO=hello}       | world                | ""              | FOO=hello       |
| ${FOO:?hello}      | world                | ошибка, exit    | ошибка, exit    |
| ${FOO?hello}       | world                | ""              | ошибка, exit    |
| ${FOO:+hello}      | hello                | ""              | ""              |
| ${FOO+hello}       | hello                | hello           | ""              |

А так же неименованные каналы. Про именованые каналы я уже [писал]({{ site.baseurl }}/2014/05/10/linux-%d0%b8%d0%bc%d0%b5%d0%bd%d0%be%d0%b2%d0%b0%d0%bd%d0%bd%d1%8b%d0%b5-%d0%ba%d0%b0%d0%bd%d0%b0%d0%bb%d1%8b/). А неименованые отличаются тем, что не надо делать его вручную.

**Литература**:
* [man xdotool](https://manpages.ubuntu.com/manpages/trusty/man1/xdotool.1.html)
* [POSIX: 2.6.2 Parameter Expansion](https://pubs.opengroup.org/onlinepubs/9699919799/utilities/V3_chap02.html#tag_18_06_02)
* [Anonymous and Named Pipes in Linux](https://www.baeldung.com/linux/anonymous-named-pipes)
