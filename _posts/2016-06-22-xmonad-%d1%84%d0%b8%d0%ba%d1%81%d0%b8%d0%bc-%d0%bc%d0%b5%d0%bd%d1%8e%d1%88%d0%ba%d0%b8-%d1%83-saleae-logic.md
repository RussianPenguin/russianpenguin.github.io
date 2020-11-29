---
layout: post
title: 'Xmonad: Фиксим менюшки у saleae logic'
date: 2016-06-22 23:00:40.000000000 +03:00
type: post
parent_id: '0'
published: true
password: ''
status: publish
categories:
- linux
tags:
- xmonad
meta:
  _wpcom_is_markdown: '1'
  _rest_api_published: '1'
  _rest_api_client_id: "-1"
  _publicize_job_id: '24092868621'
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2016/06/22/xmonad-%d1%84%d0%b8%d0%ba%d1%81%d0%b8%d0%bc-%d0%bc%d0%b5%d0%bd%d1%8e%d1%88%d0%ba%d0%b8-%d1%83-saleae-logic/"
---
![2016-06-22-22:49:47_409x324]({{ site.baseurl }}/assets/images/2016/06/2016-06-22-224947_409x324.png?w=300) Есть довольно хороший бюджетный логический [анализатор](https://www.saleae.com/) от saleae (и масса совместимых китайских клонов).

Что примечательно - это то, что ребята из этой конторы написали софт под все платформы (и не забыли линупс конечно же).

Софт написан на qt. И выглядит как бы совсем нестандартно.

В обычных DE с ним все нормально, но во всяких WM наступают грабли из-за нестандартной реализации менюшек. В частности очень сильно страдает xmonad. В нем при попытке открыть менюшки появляется цветная рамка и меню пропадает.

Фикс проблемы [есть](http://support.saleae.com/hc/communities/public/questions/204345355-menus-aren-t-working-under-xmonad) у саппорта saleae - нужно заигнорить окна с определенным классом добавив соответствующее правило к оконным хукам.

[code]className =? "Logic" --\> doIgnore[/code]

