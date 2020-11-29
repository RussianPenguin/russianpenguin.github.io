---
layout: post
title: Мониторинг обмена данными с serial-портом
date: 2014-12-17 22:02:07.000000000 +03:00
type: post
parent_id: '0'
published: true
password: ''
status: publish
categories:
- Разработка
tags:
- отладка
- python
meta:
  _wpcom_is_markdown: '1'
  sharing_disabled: '1'
  _wpas_skip_facebook: '1'
  _wpas_skip_google_plus: '1'
  _wpas_skip_twitter: '1'
  _wpas_skip_linkedin: '1'
  _wpas_skip_tumblr: '1'
  _wpas_skip_path: '1'
  _publicize_pending: '1'
  _rest_api_published: '1'
  _rest_api_client_id: "-1"
  _edit_last: '13696577'
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2014/12/17/%d0%bc%d0%be%d0%bd%d0%b8%d1%82%d0%be%d1%80%d0%b8%d0%bd%d0%b3-%d0%be%d0%b1%d0%bc%d0%b5%d0%bd%d0%b0-%d0%b4%d0%b0%d0%bd%d0%bd%d1%8b%d0%bc%d0%b8-%d1%81-serial-%d0%bf%d0%be%d1%80%d1%82%d0%be%d0%bc/"
---
Иногда надо сниффать serial-порт на предмет того, что туда пишется/читается софтиной.  
Есть сниффер jpnevulator.

[code]jpnevulator --timing-print --tty /dev/ttyACM0[/code]

Но он не совсем удобен. Так как он не сниффер на смом деле, а простой ридер-писатель для порта.  
Поэтому если запустить его совместно с другой софтиной, то она данных на порту не увидит.

А нам нужен именно сниффер.

Можно создать виртуальный порт с помощью pyserial. И читать/писать данный и реального в виртуальный порт и наоборот (MITM), но стоит ли? :)

Воспользуемся strace!

[code language="shell"]$ strace -e trace=read,write python terminal.py  
# тут я вырезал кусок, который относится к питону  
write(3, "P", 1)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; = 1  
write(3, "P", 1)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; = 1  
read(3, "P", 1)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; = 1  
read(3, ";\26\225\320\0k,\r\0", 9)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; = 9  
write(1, "3B 16 95 D0 0 6B 2C D 0\n", 243B 16 95 D0 0 6B 2C D 0  
) = 24  
write(3, "S\3\377\0\377", 5)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; = 5  
read(3, "\377\0\377", 3)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; = 3  
write(1, "FF 0 FF\n", 8FF 0 FF  
)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; = 8  
write(3, "S\5\240\244\0\0\2", 7)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; = 7  
read(3, "\244", 1)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; = 1  
write(3, "S\2?\0", 4)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; = 4  
read(3, "\237#", 2)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; = 2  
write(1, "9F 23\n", 69F 23  
)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; = 6  
write(3, "S\5\240\300\0\0#", 7)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; = 7  
read(3, "\300\0\0Z\0?\0\1\0\0\0\0\0\26\263\3\7\4\0\203\212\203\212\0\3\0\0Z\0\0\0Z"..., 38) = 38  
write(1, "C0 0 0 5A 0 3F 0 1 0 0 0 0 0 16 "..., 89C0 0 0 5A 0 3F 0 1 0 0 0 0 0 16 B3 3 7 4 0 83 8A 83 8A 0 3 0 0 5A 0 0 0 5A 0 2F 6 2 90 0  
) = 89  
write(3, "H", 1)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; = 1  
+++ exited with 0 +++[/code]

И вот уже вожделенный протокол обмена на экране. :)

