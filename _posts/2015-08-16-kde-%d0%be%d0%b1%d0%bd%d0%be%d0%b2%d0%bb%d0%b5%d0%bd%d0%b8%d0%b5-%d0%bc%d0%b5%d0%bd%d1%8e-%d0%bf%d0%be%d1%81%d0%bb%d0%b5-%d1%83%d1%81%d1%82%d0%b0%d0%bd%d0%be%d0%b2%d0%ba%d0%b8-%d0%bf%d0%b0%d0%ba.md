---
layout: post
title: 'KDE: обновление меню после установки пакетов'
date: 2015-08-16 19:33:47.000000000 +03:00
type: post
parent_id: '0'
published: true
password: ''
status: publish
categories:
- HowTo
tags:
- kde
- linux
meta:
  _wpcom_is_markdown: '1'
  sharing_disabled: '1'
  _rest_api_published: '1'
  _rest_api_client_id: "-1"
  _publicize_job_id: '13787705481'
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2015/08/16/kde-%d0%be%d0%b1%d0%bd%d0%be%d0%b2%d0%bb%d0%b5%d0%bd%d0%b8%d0%b5-%d0%bc%d0%b5%d0%bd%d1%8e-%d0%bf%d0%be%d1%81%d0%bb%d0%b5-%d1%83%d1%81%d1%82%d0%b0%d0%bd%d0%be%d0%b2%d0%ba%d0%b8-%d0%bf%d0%b0%d0%ba/"
---
KDE \>= 4 страдает одной проблемой: поставите вы пакет, который добавляет иконку в меню, а иконки там нет. И чтобы она появилась надо сделать релогин.  
Решается как всегда просто. Проблема из-за того, что в одном из файлов созданного меню появляется ошибка синтаксиса. И kbuildsycoca4 не может нормально отработать.

Выглядит примерно так:  
```
$ kbuildsycoca4  
kbuildsycoca4 running...  
kbuildsycoca4(3701) VFolderMenu::loadDoc: Parse error in "/home/penguin/.config/menus/applications-merged/xdg-desktop-menu-dummy.menu" , line 1 , col 1 : "unexpected end of file"  

```

Вот надо файл в котором у него парс эррор удалить. Некоторое время иконки будут появляться в меню сразу. А потом опять та же история (удалить файл). В трекере обещали, что багу пофиксят, Но это еще нескоро.

Так же можно запускать kbuildsycoca4 руками для ребилда меню (вдруг вы кастомную иконку добавили в ~/.local/share/application.

