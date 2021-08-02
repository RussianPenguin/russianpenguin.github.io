---
layout: post
title: Обновление нескольких git-репозитариев в папке
date: 2016-03-08 23:17:00.000000000 +03:00
type: post
categories:
- Разработка
- HowTo
tags:
- bash
- git
- linux
permalink: "/2016/03/08/%d0%be%d0%b1%d0%bd%d0%be%d0%b2%d0%bb%d0%b5%d0%bd%d0%b8%d0%b5-%d0%bd%d0%b5%d1%81%d0%ba%d0%be%d0%bb%d1%8c%d0%ba%d0%b8%d1%85-git-%d1%80%d0%b5%d0%bf%d0%be%d0%b7%d0%b8%d1%82%d0%b0%d1%80%d0%b8%d0%b5%d0%b2/"
---
![2016-03-08-22:48:31_580x134]({{ site.baseurl }}/assets/images/2016/03/2016-03-08-224831_580x134.png?w=150) Порой у нас в каталоге накапливается много-много git-репозитариев, которые хочется обновить в один заход. Для этого есть маленький скрипт, который обновляет все репозитарии, которые сможет найти в папке, переданной в качестве аргумента.

```shell
#!/bin/bash

if test "$#" -ne 1; then  
 echo "usage: $0 <dirname>"  
 echo "Find and update all git repos in specified folder"  
 exit 1
fi

if [-d $1]; then  
 find $1 -type d -name .git | xargs -n 1 dirname | sort | while read line; do echo "Update repo $line" && pushd `pwd` \> /dev/null && cd $line && git pull && popd \> /dev/null; done  
else  
 echo "\"$1\" does not exists"  
fi
```

Использование очень простое

```shell
$ gitup ~/projects
```

[Репозитарий на github](https://github.com/RussianPenguin/cliUtils).

