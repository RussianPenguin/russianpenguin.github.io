---
layout: post
title: Intel Galileo IDE и Linux
date: 2015-01-09 19:27:17.000000000 +03:00
type: post
categories:
- HowTo
- JFF
tags:
- arduino
- galileo
- ide
- linux
permalink: "/2015/01/09/intel-galileo-ide-%d0%b8-linux/"
---
Если мы запускаем первый раз IDE для Intel Galileo, то можно увидать очень интересную картину в консоли

```
$ arduino-1.5.3-Intel.1.0.4/arduino  
Board arduino:edison:izmir_ec doesn't define a 'build.board' preference. Auto-set to: EDISON_IZMIR_EC  
Board arduino:x86:izmir_fd doesn't define a 'build.board' preference. Auto-set to: X86_IZMIR_FD  
Board arduino:x86:izmir_fg doesn't define a 'build.board' preference. Auto-set to: X86_IZMIR_FG  
Experimental: JNI_OnLoad called.  
Stable Library  
=========================================  
Native lib Version = RXTX-2.1-7  
Java lib Version = RXTX-2.1-7  
check_group_uucp(): error testing lock file creation Error details:Отказано в доступеcheck_lock_status: No permission to create lock file.  
please see: How can I use Lock Files with rxtx? in INSTALL  
check_group_uucp(): error testing lock file creation Error details:Отказано в доступеcheck_lock_status: No permission to create lock file.  
please see: How can I use Lock Files with rxtx? in INSTALL  
check_group_uucp(): error testing lock file creation Error details:Отказано в доступеcheck_lock_status: No permission to create lock file.  
please see: How can I use Lock Files with rxtx? in INSTALL  
check_group_uucp(): error testing lock file creation Error details:Отказано в доступеcheck_lock_status: No permission to create lock file.  
please see: How can I use Lock Files with rxtx? in INSTALL  
check_group_uucp(): error testing lock file creation Error details:Отказано в доступеcheck_lock_status: No permission to create lock file.  
please see: How can I use Lock Files with rxtx? in INSTALL  
check_group_uucp(): error testing lock file creation Error details:Отказано в доступеcheck_lock_status: No permission to create lock file.  
please see: How can I use Lock Files with rxtx? in INSTALL  
check_group_uucp(): error testing lock file creation Error details:Отказано в доступеcheck_lock_status: No permission to create lock file.  
please see: How can I use Lock Files with rxtx? in INSTALL  
check_group_uucp(): error testing lock file creation Error details:Отказано в доступеcheck_lock_status: No permission to create lock file.  
please see: How can I use Lock Files with rxtx? in INSTALL  
check_group_uucp(): error testing lock file creation Error details:Отказано в доступеcheck_lock_status: No permission to create lock file.  
please see: How can I use Lock Files with rxtx? in INSTALL  
check_group_uucp(): error testing lock file creation Error details:Отказано в доступеcheck_lock_status: No permission to create lock file.  
please see: How can I use Lock Files with rxtx? in INSTALL  
check_group_uucp(): error testing lock file creation Error details:Отказано в доступеcheck_lock_status: No permission to create lock file.  
please see: How can I use Lock Files with rxtx? in INSTALL  
check_group_uucp(): error testing lock file creation Error details:Отказано в доступеcheck_lock_status: No permission to create lock file.  
please see: How can I use Lock Files with rxtx? in INSTALL  
check_group_uucp(): error testing lock file creation Error details:Отказано в доступеcheck_lock_status: No permission to create lock file.  
please see: How can I use Lock Files with rxtx? in INSTALL  
check_group_uucp(): error testing lock file creation Error details:Отказано в доступеcheck_lock_status: No permission to create lock file.  
please see: How can I use Lock Files with rxtx? in INSTALL  
check_group_uucp(): error testing lock file creation Error details:Отказано в доступеcheck_lock_status: No permission to create lock file.  
please see: How can I use Lock Files with rxtx? in INSTALL  
check_group_uucp(): error testing lock file creation Error details:Отказано в доступеcheck_lock_status: No permission to create lock file.  
please see: How can I use Lock Files with rxtx? in INSTALL
```

Ага. А решается все очень просто.

1. Наш пользователь должен быть в группе lock
2. Для папки /run/lock (/var/lock в некоторых дистрибах) должны стоять права root:lock (776)
