---
layout: post
title: 'gdb: запуск приложения'
date: 2013-11-14 14:06:28.000000000 +04:00
type: post
categories:
- HowTo
tags:
- отладка
permalink: "/2013/11/14/gdb_how_to_run_application/"
---
установка новых аргументов для запуска программы

```
set args <args>
```

просмотр аргументов

```
show args
```

переход по ассемблерным командам

```
ni, si
```

Отобразить следующие n инструкций

```
display/ni $pc
```

Поиск в памяти

```
find
```
