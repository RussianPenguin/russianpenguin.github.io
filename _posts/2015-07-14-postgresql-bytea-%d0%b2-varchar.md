---
layout: post
title: 'PostgreSQL: bytea в varchar'
date: 2015-07-14 15:14:43.000000000 +03:00
type: post
categories:
- Разработка
tags:
- sql
permalink: "/2015/07/14/postgresql-bytea-%d0%b2-varchar/"
---
[![bytea]({{ site.baseurl }}/assets/images/2015/07/d0b2d18bd0b4d0b5d0bbd0b5d0bdd0b8d0b5_009.png)](/2015/07/d0b2d18bd0b4d0b5d0bbd0b5d0bdd0b8d0b5_009.png)Есть такой тип: [bytea](http://www.postgresql.org/docs/8.4/static/datatype-binary.html). При попытке вдеслть селект на него в консоли он отображен не будет. А в Pgadmin будет заглушка "<двоичные данные>". Это совсем неудобно когда хочется посмотреть, что же там скрывается.

Но все решается просто :)

```sql
select convert_from(body, 'utf8') from megatable
```

Вторым аргументом convert_from выступает кодировка исходного текста.  
&nbsp;

