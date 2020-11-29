---
layout: post
title: 'PostgreSQL: bytea в varchar'
date: 2015-07-14 15:14:43.000000000 +03:00
type: post
parent_id: '0'
published: true
password: ''
status: publish
categories:
- Разработка
tags:
- sql
meta:
  _wpcom_is_markdown: '1'
  sharing_disabled: '1'
  _rest_api_published: '1'
  _rest_api_client_id: "-1"
  _publicize_job_id: '12717385138'
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2015/07/14/postgresql-bytea-%d0%b2-varchar/"
---
[![bytea]({{ site.baseurl }}/assets/images/2015/07/d0b2d18bd0b4d0b5d0bbd0b5d0bdd0b8d0b5_009.png?w=150)](https://russianpenguin.files.wordpress.com/2015/07/d0b2d18bd0b4d0b5d0bbd0b5d0bdd0b8d0b5_009.png)Есть такой тип: [bytea](http://www.postgresql.org/docs/8.4/static/datatype-binary.html). При попытке вдеслть селект на него в консоли он отображен не будет. А в Pgadmin будет заглушка "\<двоичные данные\>". Это совсем неудобно когда хочется посмотреть, что же там скрывается.

Но все решается просто :)

[code lang="sql"]select convert\_from(body, 'utf8') from megatable[/code]

Вторым аргументом convert\_from выступает кодировка исходного текста.  
&nbsp;

