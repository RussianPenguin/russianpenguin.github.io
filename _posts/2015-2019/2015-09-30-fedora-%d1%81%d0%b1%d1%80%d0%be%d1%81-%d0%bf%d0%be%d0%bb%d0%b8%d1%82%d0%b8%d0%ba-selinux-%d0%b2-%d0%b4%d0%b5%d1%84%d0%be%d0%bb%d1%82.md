---
layout: post
title: 'Fedora: сброс политик SELinux в дефолт'
date: 2015-09-30 21:11:06.000000000 +03:00
type: post
categories: []
tags:
- linux
- selinux
permalink: "/2015/09/30/fedora-%d1%81%d0%b1%d1%80%d0%be%d1%81-%d0%bf%d0%be%d0%bb%d0%b8%d1%82%d0%b8%d0%ba-selinux-%d0%b2-%d0%b4%d0%b5%d1%84%d0%be%d0%bb%d1%82/"
---
![getenforce]({{ site.baseurl }}/assets/images/2015/09/getenforce.png)

SELinux штука очень мощная и полезная. Но вот незадача: иногда ее настроить бывает слишком тяжело. И можно просто довести систему до состояния, когда она не загружается (особенно если играться с политикой доступа к каталогам :)).  
Для таких случаев нужно уметь сбрасывать политики в дефолтное состояние.  
Сначала загружаем систему в failback-режиме (там selinux отключен).

Теперь  
```
setenforce 0  
# dnf erase selinux-policy selinux-policy-targeted  
# dnf mv /etc/selinux/targeted{,.backup}  
# reboot
```

После перезагрузки системы selinux будет отключен.

Нужно поставить пакеты, которые мы удаляли.  
```
# dnf install selinux-policy selinux-policy-targeted
```

И теперь включить сам механизм. Для этого отредактируем файл /etc/selinux/config и заменим  
```
SELINUX=disabled
```  
на  
```
SELINUX=enforcing
```

После очередной перезагрузки мы увидим, что с SELinux все хорошо.

Если же вам повезло и вы можете загрузить систему в нормальном режиме, то шаги выше можно делать без перезагрузок.

[Документация](https://docs.fedoraproject.org/en-US/Fedora/21/html/SELinux_Users_and_Administrators_Guide/index.html).

