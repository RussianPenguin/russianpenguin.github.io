---
layout: post
title: 'Symfony2: страница 404 и авторизация'
type: post
categories:
- Разработка
tags:
- php
- symfony2
permalink: "/2015/09/03/symfony2-%d1%81%d1%82%d1%80%d0%b0%d0%bd%d0%b8%d1%86%d0%b0-404-%d0%b8-%d0%b0%d0%b2%d1%82%d0%be%d1%80%d0%b8%d0%b7%d0%b0%d1%86%d0%b8%d1%8f/"
---
Все знают, что в symfony2 404я страница не попадает под действие фаерволов. А это значит, что даже пытаясь кастомизировать 404ую страницу мы не сможем получить имя пользователя и его роль в системе. Так как механизм авторизации попросту не загружается.

Однако, существует решение, которое позволяет кастомизировать страницу 404 с учетом пользовательских данных.

Для этого нам надо завести роут подпадающий под действия фаерволлов и при этом откликающийся на любой (!) введенный адрес перехода.

Экшн будет отдавать эксепшн NotFoundHttpException. Таким образом мы получим ситуацию, когда при переходе на 404ую все фаерволы запущены и данные пользователя загружены.

```php
namespace ProjectBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;  
use Symfony\Bundle\FrameworkBundle\Controller\Controller;  
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller  
{  
 //...

/**  
 * Данный роут перехватывает все переходы в системе, которые не охвачены другими роутами.  
 * @Route("/{path}", name="_inner404Redirect")  
 */  
 public function inner404Redirect()  
 {  
 throw new NotFoundHttpException();  
 }  
}
```

[Источник](https://github.com/symfony/symfony/issues/8414)

