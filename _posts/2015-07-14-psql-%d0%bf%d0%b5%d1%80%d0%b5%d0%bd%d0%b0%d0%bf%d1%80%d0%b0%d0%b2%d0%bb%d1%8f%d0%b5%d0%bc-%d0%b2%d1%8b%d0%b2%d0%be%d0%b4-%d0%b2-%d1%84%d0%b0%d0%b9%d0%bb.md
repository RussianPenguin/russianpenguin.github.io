---
layout: post
title: 'psql: перенаправляем вывод в файл'
date: 2015-07-14 15:22:14.000000000 +03:00
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
  _publicize_job_id: '12717577364'
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2015/07/14/psql-%d0%bf%d0%b5%d1%80%d0%b5%d0%bd%d0%b0%d0%bf%d1%80%d0%b0%d0%b2%d0%bb%d1%8f%d0%b5%d0%bc-%d0%b2%d1%8b%d0%b2%d0%be%d0%b4-%d0%b2-%d1%84%d0%b0%d0%b9%d0%bb/"
---
[code lang="sql"]=\> copy (select 42) to '/tmp/answer';[/code]  
Первая разновидность команды копирования выполняет перенаправление вывода в указанный файл на **удаленной** машине. Ждя ее исполнения пользователь должен обладать правами рута.

[code lang="sql"]=\> /copy (select 42) to '/tmp/answer'[/code]

Вторая разновидность - это метакоманда клиента psql которой не требуются права суперпользователя и запись выполняется в файл на локальной машине.

Стоит обратить внимание, что первый вариант - это команда sql (поэтому за ней и идет точка с запятой), а вторая - это именно метакоманда. окончание - всегда символ новой строки.

