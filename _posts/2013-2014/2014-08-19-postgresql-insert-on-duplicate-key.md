---
layout: post
title: 'PostgreSQL: insert on duplicate key'
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

Но это легко [эмулируется](http://stackoverflow.com/a/6527838/1216190) последовательностью запросов.

```sql
UPDATE table SET field='C', field2='Z' WHERE id=3;  
INSERT INTO table (id, field, field2)  
       SELECT 3, 'C', 'Z'  
       WHERE NOT EXISTS (SELECT 1 FROM table WHERE id=3);
```

