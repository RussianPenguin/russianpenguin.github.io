---
layout: post
title: 'Fedora/CentOS: Не работает vpn подключение по pptp'
date: 2018-05-06 19:18:04.000000000 +03:00
type: post
parent_id: '0'
published: true
password: ''
status: publish
categories:
- HowTo
tags:
- firewalld
- linux
- vpn
meta:
  _wpcom_is_markdown: '1'
  _rest_api_published: '1'
  _rest_api_client_id: "-1"
  _publicize_job_id: '17549446551'
  timeline_notification: '1525623485'
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2018/05/06/fedora-centos-%d0%bd%d0%b5-%d1%80%d0%b0%d0%b1%d0%be%d1%82%d0%b0%d0%b5%d1%82-vpn-%d0%bf%d0%be%d0%b4%d0%ba%d0%bb%d1%8e%d1%87%d0%b5%d0%bd%d0%b8%d0%b5-%d0%bf%d0%be-pptp/"
excerpt: Не всегда в дистрибутивах получается подключиться по протоколу pptp к удаленному
  серверу. Часто появляются ошибки обмена lcp-пакетами. Попробуем разобраться, что
  происходит.
---
![How_to_vpn_work]({{ site.baseurl }}/assets/images/2018/05/how_to_vpn_work.png?w=150)Иногда нам нужно подключится по протоколу PPTP к рабочему vpn (корпоративная сеть). А соединения не происходит и система показывается, что произошел сбой при попытке подключиться.

В логах наблюдается что-то подобное.

[code]май 06 17:45:37 localhost.localdomain pppd[17294]: Connection terminated.  
май 06 17:45:37 localhost.localdomain pppd[17294]: LCP: timeout sending Config-Requests[/code]

Можно запустить wireshark и посмотреть, что на каждый lcp-запрос есть lcp-ответ.

![2018-05-06-19:10:05_358x148]({{ site.baseurl }}/assets/images/2018/05/2018-05-06-191005_358x148.png)

Можно просто попробовать включить данный протокол поскольку firewalld не имеет правила по-умолчанию, которое позволяет системе принимать нужные пакеты. А именно в gre инкапсулируются пакеты lcp, которые отвечают за настройку соединения).

В интернетах часто рекомендуют делать нативное правило как-то так.

[code lang="shell"]$ sudo firewall-cmd --direct --add-rule ipv4 filter INPUT 0 -p gre -j ACCEPT  
$ sudo firewall-cmd --direct --add-rule ipv6 filter INPUT 0 -p gre -j ACCEPT  
$ sudo firewall-cmd --reload[/code]

Не делайте так - потом этими правилами сложнее управлять.

Попробуем разрешить подключения более элегантно, используя имеющиеся абстракции.

Сначала добавим временные правила чтобы проверить работоспособность и проверим, что протокол добавился в нужную зону.

[code lang="shell"]$ sudo firewall-cmd --zone=home --add-protocol=gre  
$ sudo firewall-cmd --zone=home --query-protocol=gre[/code]

Система должна сказать yes. Если попытка подключения прошла успешно, то стоит добавить правила на постоянной основе.

Не забудьте указать нужную зону через параметр --zone как в первом, так и во втором случае.

[code lang="shell"]$ sudo firewall-cmd --permanent --zone=home --add-protocol=gre  
$ sudo firewall-cmd --permanent --zone=home --query-protocol=gre  
$ sudo firewall-cmd --reload[/code]

Дальше могут быть проблемы с авторизацией, но это совсем другая история.

## Литература

- [rfc2637](https://www.ietf.org/rfc/rfc2637.txt)
- [Wikipedia: GRE (протокол)](https://ru.wikipedia.org/wiki/GRE_(протокол))
- [Лекция: технология туннелрования](https://www.intuit.ru/studies/courses/14248/1285/lecture/24211?page=1)
