---
layout: post
title: Raspbian - это не Debian
date: 2016-02-26 22:45:34.000000000 +03:00
type: post
parent_id: '0'
published: true
password: ''
status: publish
categories: []
tags:
- linux
- raspberry pi
meta:
  _wpcom_is_markdown: '1'
  _rest_api_published: '1'
  _rest_api_client_id: "-1"
  _publicize_job_id: '20209007691'
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2016/02/26/raspbian-%d1%8d%d1%82%d0%be-%d0%bd%d0%b5-debian/"
---
![img_raspbian_logo]({{ site.baseurl }}/assets/images/2016/02/img_raspbian_logo.png) [Raspbian](https://www.raspbian.org) - это не Debian. Хотя картинка нас убежлает в обратном.

В чем проблема? Проблема в обозначении архитектуры.

Давеча захотел я поставить на малинку пакет из репозитария собранного под Debian.

Прописал как положено в /etc/apt/sources.list.d/\<repository\>.list

```
deb \<repourl\> wheezy main
```

Установил и получил segmentation fault. Казалось бы - архитектура armhf (как на малинке), но почему-то не работает.

А дело все в том, что разработчики raspbian перекомпилировали дебиановский armhf (ARMv7) для совместимости с ARMv6, но название архитектуры не поменяли. В итоге попытки установить какой-нибудь пакет \*.armhf из дебиана может закончится сегментейшнфаултом.

Для того, чтобы решить все проблемы с софтом из дебиановских репозитариев нужно использовать архитектуру armel, которой разработчики обозначают как раз v6. И явно ее указывать в настройках.

В итоге конфигурация репозитариев deb будет выглядеть так:

```
deb [arch=armel] \<repourl\> wheezy main
```

Ключевое здесь - добавить спецификацию архитектуры как [arch=armel] и таким образом можно успешно подключать даже репозитарии с какого-нибудь ланчпада.

[Пруф](https://www.raspbian.org/RaspbianFAQ#What_is_Raspbian.3F)

