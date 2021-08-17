---
layout: post
title: 'Symfony2: cannot redeclarate class'
date: 2015-05-22 17:42:54.000000000 +03:00
type: post
parent_id: '0'
published: true
password: ''
status: publish
categories:
- Разработка
tags:
- php
- symfony2
meta:
  _wpcom_is_markdown: '1'
  sharing_disabled: '1'
  _publicize_job_id: '10892518145'
  _rest_api_published: '1'
  _rest_api_client_id: "-1"
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2015/05/22/symfony2-cannot-redeclarate-class/"
---
```
Fatal error: include() [<a href="http://contoso.com/app/function.include">function.include</a>]: Cannot redeclare class symfony\bundle\frameworkbundle\frameworkbundle in /srv/www/contoso.com/vendor/composer/ClassLoader.php on line <i>412</i>
```

Да-да. Есть такая противная ошибка.

Она лечится либо отключением apc, либо установкой для него следующего набора опций

```
apc.include_once_override = 0  
apc.canonicalize = 0  
apc.stat = 0
```

