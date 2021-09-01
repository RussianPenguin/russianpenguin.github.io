---
layout: post
title: 'Bash: копирование файлов из списка'
date: 2015-10-07 18:12:49.000000000 +03:00
type: post
categories:
- Разработка
- linux
tags:
- bash
permalink: "/2015/10/07/bash-%d0%ba%d0%be%d0%bf%d0%b8%d1%80%d0%be%d0%b2%d0%b0%d0%bd%d0%b8%d0%b5-%d1%84%d0%b0%d0%b9%d0%bb%d0%be%d0%b2-%d0%b8%d0%b7-%d1%81%d0%bf%d0%b8%d1%81%d0%ba%d0%b0/"
---
![Copy files (screenshot)]({{ site.baseurl }}/assets/images/2015/10/d0b2d18bd0b4d0b5d0bbd0b5d0bdd0b8d0b5_028.png) Задача: у нас есть файл со списком стилей/скриптов/бинарников (нужное подчернуть) которые надо скопировать или переместить в другое место.  
Да. Такие задачи бывают. :)

Допустим выглядит файл как-то так  
```shell
$ cat css.txt  
css/reset-ls.css  
css/b-browser.css  
css/reg-form.css  
css/old/pop-up.css
```

Пути либо относительные, либо полные.

Скопировать все в новый локейшн можно простым однострочником  
```shell
$ for i in $(cat css.txt); do cp $i /tmp/; done
```

