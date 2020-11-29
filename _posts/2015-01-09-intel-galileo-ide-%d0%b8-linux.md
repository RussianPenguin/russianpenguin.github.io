---
layout: post
title: Intel Galileo IDE и Linux
date: 2015-01-09 19:27:17.000000000 +03:00
type: post
parent_id: '0'
published: true
password: ''
status: publish
categories:
- HowTo
- JFF
tags:
- arduino
- galileo
- ide
- linux
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
  _oembed_335f784bd312abcee7860548346b76e0: "{{unknown}}"
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2015/01/09/intel-galileo-ide-%d0%b8-linux/"
---
Если мы запускаем первый раз IDE для Intel Galileo, то можно увидать очень интересную картину в консоли

[code]$ arduino-1.5.3-Intel.1.0.4/arduino  
Board arduino:edison:izmir\_ec doesn't define a 'build.board' preference. Auto-set to: EDISON\_IZMIR\_EC  
Board arduino:x86:izmir\_fd doesn't define a 'build.board' preference. Auto-set to: X86\_IZMIR\_FD  
Board arduino:x86:izmir\_fg doesn't define a 'build.board' preference. Auto-set to: X86\_IZMIR\_FG  
Experimental: JNI\_OnLoad called.  
Stable Library  
=========================================  
Native lib Version = RXTX-2.1-7  
Java lib Version = RXTX-2.1-7  
check\_group\_uucp(): error testing lock file creation Error details:Отказано в доступеcheck\_lock\_status: No permission to create lock file.  
please see: How can I use Lock Files with rxtx? in INSTALL  
check\_group\_uucp(): error testing lock file creation Error details:Отказано в доступеcheck\_lock\_status: No permission to create lock file.  
please see: How can I use Lock Files with rxtx? in INSTALL  
check\_group\_uucp(): error testing lock file creation Error details:Отказано в доступеcheck\_lock\_status: No permission to create lock file.  
please see: How can I use Lock Files with rxtx? in INSTALL  
check\_group\_uucp(): error testing lock file creation Error details:Отказано в доступеcheck\_lock\_status: No permission to create lock file.  
please see: How can I use Lock Files with rxtx? in INSTALL  
check\_group\_uucp(): error testing lock file creation Error details:Отказано в доступеcheck\_lock\_status: No permission to create lock file.  
please see: How can I use Lock Files with rxtx? in INSTALL  
check\_group\_uucp(): error testing lock file creation Error details:Отказано в доступеcheck\_lock\_status: No permission to create lock file.  
please see: How can I use Lock Files with rxtx? in INSTALL  
check\_group\_uucp(): error testing lock file creation Error details:Отказано в доступеcheck\_lock\_status: No permission to create lock file.  
please see: How can I use Lock Files with rxtx? in INSTALL  
check\_group\_uucp(): error testing lock file creation Error details:Отказано в доступеcheck\_lock\_status: No permission to create lock file.  
please see: How can I use Lock Files with rxtx? in INSTALL  
check\_group\_uucp(): error testing lock file creation Error details:Отказано в доступеcheck\_lock\_status: No permission to create lock file.  
please see: How can I use Lock Files with rxtx? in INSTALL  
check\_group\_uucp(): error testing lock file creation Error details:Отказано в доступеcheck\_lock\_status: No permission to create lock file.  
please see: How can I use Lock Files with rxtx? in INSTALL  
check\_group\_uucp(): error testing lock file creation Error details:Отказано в доступеcheck\_lock\_status: No permission to create lock file.  
please see: How can I use Lock Files with rxtx? in INSTALL  
check\_group\_uucp(): error testing lock file creation Error details:Отказано в доступеcheck\_lock\_status: No permission to create lock file.  
please see: How can I use Lock Files with rxtx? in INSTALL  
check\_group\_uucp(): error testing lock file creation Error details:Отказано в доступеcheck\_lock\_status: No permission to create lock file.  
please see: How can I use Lock Files with rxtx? in INSTALL  
check\_group\_uucp(): error testing lock file creation Error details:Отказано в доступеcheck\_lock\_status: No permission to create lock file.  
please see: How can I use Lock Files with rxtx? in INSTALL  
check\_group\_uucp(): error testing lock file creation Error details:Отказано в доступеcheck\_lock\_status: No permission to create lock file.  
please see: How can I use Lock Files with rxtx? in INSTALL  
check\_group\_uucp(): error testing lock file creation Error details:Отказано в доступеcheck\_lock\_status: No permission to create lock file.  
please see: How can I use Lock Files with rxtx? in INSTALL[/code]

Ага. А решается все очень просто.

1. Наш пользователь должен быть в группе lock
2. Для папки /run/lock (/var/lock в некоторых дистрибах) должны стоять права root:lock (776)
