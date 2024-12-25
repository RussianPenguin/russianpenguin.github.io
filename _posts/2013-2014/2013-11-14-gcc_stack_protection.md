---
layout: post
title: 'gcc: защита от переполнения стека'
type: post
categories:
- HowTo
tags:
- программирование
permalink: "/2013/11/14/g%d1%81%d1%81_stack_protection/"
---
Есть необходимость выполнить компиляцию кода в gdb без защиты от переполнения стека

```
-fno-stack-protector
```

и без защиты от испольнения кода в стеке

```
-z execstack
```
