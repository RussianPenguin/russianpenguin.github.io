---
layout: post
title: 'Git: выделяем глобальный репозитарий для проекта'
date: 2014-07-27 22:04:02.000000000 +04:00
type: post
categories:
- Разработка
tags:
- git
permalink: "/2014/07/27/git-%d0%b2%d1%8b%d0%b4%d0%b5%d0%bb%d1%8f%d0%b5%d0%bc-%d0%b3%d0%bb%d0%be%d0%b1%d0%b0%d0%bb%d1%8c%d0%bd%d1%8b%d0%b9-%d1%80%d0%b5%d0%bf%d0%be%d0%b7%d0%b8%d1%82%d0%b0%d1%80%d0%b8%d0%b9-%d0%b4%d0%bb%d1%8f/"
---
Иногда мы копируем папку с проектом на отдельную машину, а затем пытаемся использовать его в качестве мастер-репозитария.  
 Но при попытке сделать пуш в этот репозитарий нас подстерегает ошибка.```
$ git push origin master  
Counting objects: 30, done.  
Delta compression using up to 4 threads.  
Compressing objects: 100% (16/16), done.  
Writing objects: 100% (17/17), 3.31 KiB | 0 bytes/s, done.  
Total 17 (delta 4), reused 0 (delta 0)  
remote: error: refusing to update checked out branch: refs/heads/master  
remote: error: By default, updating the current branch in a non-bare repository  
remote: error: is denied, because it will make the index and work tree inconsistent  
remote: error: with what you pushed, and will require 'git reset --hard' to match  
remote: error: the work tree to HEAD.  
remote: error:  
remote: error: You can set 'receive.denyCurrentBranch' configuration variable to  
remote: error: 'ignore' or 'warn' in the remote repository to allow pushing into  
remote: error: its current branch; however, this is not recommended unless you  
remote: error: arranged to update its work tree to match what you pushed in some  
remote: error: other way.  
remote: error:  
remote: error: To squelch this message and still keep the default behaviour, set  
remote: error: 'receive.denyCurrentBranch' configuration variable to 'refuse'.  
To /project/path  
&nbsp;! [remote rejected] master -> master (branch is currently checked out)  
error: failed to push some refs to '/project/path'
```

Вообще почему она возникает-то? Разве гит это не распределенная система контроля версий? Распределенная и даже чутка децентрализованная. Совсем-совсем распределенная. А это значит, что пушить изменения можно не только на одно хранилище, а вообще в любой репозитарий. Хоть чуваку за соседний комп. Таким образом разработчикии могут делиться своими наработками.

Но это подразумевает один нюанс: нельзя пушить в ту ветку удаленного репозитария с которой сейчас идет работа.

Суть сообщения в том, что в удаленном репозитарии ветвь, которую мы хотим запушить сейчас, активна (т.е. сделан чекаут на удаленной машине).

А значит и правки в эту ветвь якобы удаленной машиной вносятся (слава роботам!).

Способ первый. Мы заходим в репозитарий и просто чекаутим другую ветку проекта. Профит. :)

Способ второй. Мы узнаем, что существует два типа хранилищ: bare и non bare. Дефолтно при выполнении git init будет создано хранилище второго типа (и при чекауте чужого репощитария так же создается локальный non-bare репозитарий).

Bare значит, то это не рабочий репозитарий, а просто склад веток, куда каждый может пушить. И если мы попробуем поработать с ним напрямую (покоммитить), то нас ждет облом.

Гитхаб предоставляет хранилища именно такого типа.

Второй же тип - это репозитарии разработчика. Активная в данный момент ветвь блокируется на изменение от нелокальных клиентов.

А что делать?

Если реп у нас удаленный и для совместной работы - конвертируем его в bare

```shell
git config --bool core.bare true
```

Если нет - смотрим выше.

А еще можно сказать иниту, чтобы создавал bare сразу.

```
git init --bare
```

