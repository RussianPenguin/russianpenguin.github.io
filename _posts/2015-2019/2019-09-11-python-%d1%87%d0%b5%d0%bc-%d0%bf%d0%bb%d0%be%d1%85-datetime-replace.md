---
layout: post
title: 'Python: Чем плох datetime.replace?'
date: 2019-09-11 22:46:08.000000000 +03:00
type: post
categories:
- Разработка
- HowTo
tags: []
permalink: "/2019/09/11/python-чем-плох-datetime-replace/"
---

<img src="{{ site.baseurl }}/assets/images/2019/09/d187d0b0d181d18b.jpeg" title="Чем плох datetime.replace?" alt="Чем плох datetime.replace?" class="img-fluid" />

Поговорим сегодня про даты и часовые пояса. А именно о том, почему не стоит использовать datetime.replace совместно с таймзонами из pytz если вы не уверены (вообще не стоит).

Конечно все с оговорками. Иногда так надо. Но все равно не стоит так делать.

<!--more-->

Вводные:

```python
  
>>> from datetime import datetime  
>>> import pytz  
>>> dt = datetime.strptime('2019-09-01 12:00:00', '%Y-%m-%d %H:%M:%S')  
>>> tzdata = pytz.timezone('Europe/Moscow')  
>>> dt_withreplace = dt.replace(tzinfo=tzdata)  
>>> dt_withlocalize = tzdata.localize(dt)  

```

Переводем обе даты в UTC.

```python
  
>>> dt_withlocalize.astimezone(pytz.utc)  
datetime.datetime(2019, 9, 1, 9, 0, tzinfo=<UTC>)  
>>> dt_withreplace.astimezone(pytz.utc)  
datetime.datetime(2019, 9, 1, 9, 30, tzinfo=<UTC>)  

```

Спорим, что вы ожидали вовсе не этого? Откуда взялось отставание в 30 минут при использовании replace?

Рассмотрим содержимое двух дат с таймзонами.

```python
  
>>> dt_withreplace  
datetime.datetime(2019, 9, 1, 12, 0, tzinfo=<DstTzInfo 'Europe/Moscow' LMT+2:30:00 STD>)  
>>> dt_withlocalize  
datetime.datetime(2019, 9, 1, 12, 0, tzinfo=<DstTzInfo 'Europe/Moscow' MSK+3:00:00 STD>)  

```

Ок. MSK - это хорошо, но что такое LMT?

LMT (local mean time) - местное среднее время. Если простыми словами, то это время формировалось для некоторого мередиана на основании солнечного времени и солнечных часов.

Если заглянуть в [вики](https://en.wikipedia.org/wiki/Local_mean_time), то можно прочесть, что этот тип учета времени использовался до введения часовых поясов. Так почему современный питон применил его к дате?

Нам потребуется заглянуть внутрь функции localize и replace.

Начнем с replace, проберемся через дебри к [коду](https://github.com/python/cpython/blob/ee536b2020b1f0baad1286dbd4345e13870324af/Lib/datetime.py#L1501-L1516), который выполняет замену.

Ничего подозрительного мы видим, что создается новый объект с новым tzinfo.

Вот только есть одно но. Новый tzinfo - имеет класс [DstTzInfo](https://github.com/stub42/pytz/blob/62f872054dde69e5c510094093cd6e221d96d5db/src/pytz/tzinfo.py#L156).

Он имплементирует методы объекта [datetime.tzinfo](https://github.com/python/cpython/blob/ee536b2020b1f0baad1286dbd4345e13870324af/Lib/datetime.py#L1141). И если мы чуть-чуть прогуляемся по коду, то увидим, что вызывается [utcoffset](https://github.com/python/cpython/blob/ee536b2020b1f0baad1286dbd4345e13870324af/Lib/datetime.py#L1866-L1869).

Посмотрим, что он нам вернет в tzdata.

```python
>>> tzdata.utcoffset(dt)  
datetime.timedelta(seconds=10800)  
>>> tzdata.utcoffset(dt_withreplace)  
datetime.timedelta(seconds=9000)  
>>> tzdata.utcoffset(dt_withlocalize)  
Traceback (most recent call last):  
 File "", line 1, in  
 File "/usr/lib/python3.7/site-packages/pytz/tzinfo.py", line 422, in utcoffset  
 dt = self.localize(dt, is_dst)  
 File "/usr/lib/python3.7/site-packages/pytz/tzinfo.py", line 318, in localize  
 raise ValueError('Not naive datetime (tzinfo is already set)')  
ValueError: Not naive datetime (tzinfo is already set)
```

Если мы еще чуть больше покопаемся в коде [pytz.DstTzInfo](https://github.com/stub42/pytz/blob/62f872054dde69e5c510094093cd6e221d96d5db/src/pytz/tzinfo.py#L156), то увидим, что он является прокси, который реализует все методы оригинального datetime.tzinfo, но при этом содержит в себе определения таймзон за разные периоды времени.

Когда мы пытаемся использовать его через подстановку в конструктор datetime, то ничего хорошего не будет. Он просто начнет возвращаеть первое определение часового пояса в своем внутреннем списке (_utc_transition_times, _tzinfos, _transition_info). На нашу беду первым в списке стоит LMT.

Чтобы такого казуса не произошло следует использовать метод [pytz.DstTzInfo.localize](https://github.com/stub42/pytz/blob/62f872054dde69e5c510094093cd6e221d96d5db/src/pytz/tzinfo.py#L258-L394). Именно в нем происходит вся магия выбора пояса в зависимости от даты.

