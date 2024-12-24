---
layout: post
title: 'Git: откат мержа, который не был запушен'
type: post
categories:
- Разработка
- HowTo
tags:
- git
permalink: "/2016/05/04/git-%d0%be%d1%82%d0%ba%d0%b0%d1%82-%d0%bc%d0%b5%d1%80%d0%b6%d0%b0-%d0%ba%d0%be%d1%82%d0%be%d1%80%d1%8b%d0%b9-%d0%bd%d0%b5-%d0%b1%d1%8b%d0%bb-%d0%b7%d0%b0%d0%bf%d1%83%d1%88%d0%b5%d0%bd/"
---
Иногда мы делаем мерж некоторой ветки в master и случается такое, что нам нужно по какой либо причине откатить ветку (мы не делали push еще в удаленный репозитарий), либо просто пытаемся сделать git pull забыв, что есть непрокоммиченые изменения.

При git pull мы увидим следующую картину

```
Git Pull Failed  
You have not concluded your merge (MERGE_HEAD exists). Please, commit your changes before you can merge.
```

И чтобы откатить наш неудачный мерж не нужно делать никаких reset --hard HEAD=1.

Достаточно сделать

```
git reset --hard ORIG_HEAD
```

Тем самым мы приводим локальную ветку мастера к виду, в котором она был до неудачного мержа.

