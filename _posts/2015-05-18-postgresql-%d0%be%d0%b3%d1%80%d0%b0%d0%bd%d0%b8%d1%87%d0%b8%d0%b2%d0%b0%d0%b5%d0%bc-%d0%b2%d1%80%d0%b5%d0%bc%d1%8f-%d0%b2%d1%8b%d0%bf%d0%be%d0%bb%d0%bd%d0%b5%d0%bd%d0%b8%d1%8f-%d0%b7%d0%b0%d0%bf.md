---
layout: post
title: 'PostgreSQL: ограничиваем время выполнения запроса'
date: 2015-05-18 16:23:48.000000000 +03:00
type: post
categories:
- Разработка
tags:
- sql
permalink: "/2015/05/18/postgresql-%d0%be%d0%b3%d1%80%d0%b0%d0%bd%d0%b8%d1%87%d0%b8%d0%b2%d0%b0%d0%b5%d0%bc-%d0%b2%d1%80%d0%b5%d0%bc%d1%8f-%d0%b2%d1%8b%d0%bf%d0%be%d0%bb%d0%bd%d0%b5%d0%bd%d0%b8%d1%8f-%d0%b7%d0%b0%d0%bf/"
---
```sql
db=> set statement_timeout to 100;  
SET  
db=> select pg_sleep(110);  
ERROR:  canceling statement due to statement timeout  
db=> set statement_timeout to 0;  
SET  
db=> 
```

Первым выражением установим максимальное время выполнения запроса в миллисекундах. Вторым пойдет запрос, а третьим мы снимем ограничение на время выполнения (0 - значение по умолчанию).

[Дока](http://www.postgresql.org/docs/current/static/runtime-config-client.html "Client Connection Defaults").

