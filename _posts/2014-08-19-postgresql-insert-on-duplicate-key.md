---
layout: post
title: 'PostgreSQL: insert on duplicate key'
date: 2014-08-19 15:24:36.000000000 +04:00
type: post
categories:
- Найдено в сети
- Разработка
tags:
- sql
permalink: "/2014/08/19/postgresql-insert-on-duplicate-key/"
---
Да-да. Постгрес не умеет делать

```sql
insert * on duplicate key ...
```

Но это легко&nbsp;[эмулируется](http://stackoverflow.com/a/6527838/1216190) последовательностью запросов.

```sql
UPDATE table SET field='C', field2='Z' WHERE id=3;  
INSERT INTO table (id, field, field2)  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; SELECT 3, 'C', 'Z'  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; WHERE NOT EXISTS (SELECT 1 FROM table WHERE id=3);
```

