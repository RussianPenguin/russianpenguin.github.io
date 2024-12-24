---
layout: post
title: Подсчет вхождений слов в файле
date: 2013-11-28 01:58:09.000000000 +04:00
type: post
categories:
- HowTo
tags:
- консоль
- обработка текста
permalink: "/2013/11/28/%d0%bf%d0%be%d0%b4%d1%81%d1%87%d0%b5%d1%82-%d0%b2%d1%85%d0%be%d0%b6%d0%b4%d0%b5%d0%bd%d0%b8%d0%b9-%d1%81%d0%bb%d0%be%d0%b2-%d0%b2-%d1%84%d0%b0%d0%b9%d0%bb%d0%b5/"
---
```shell
#!/bin/bash if [-f $1] then for word in $(grep -o -i -E '(^|b)S*(b|$)' $1|sort -u -f | grep -o -i -E 'w*' | sort -u -f) do word_stat=$(grep -o -i -E "(^|\W)$word($|\W)" $1 |wc -l) echo ""$word" $word_stat" done; fi;
```

```shell
$ ./wordstat.sh text.txt "a" 2578 "a2" 1 "Aaaugh" 2 "abandon" 2 "abandoned" 5 "abandonment" 1 "Aber" 2 "abilities" 1 "ability" 7 "able" 5 <cut>
```
