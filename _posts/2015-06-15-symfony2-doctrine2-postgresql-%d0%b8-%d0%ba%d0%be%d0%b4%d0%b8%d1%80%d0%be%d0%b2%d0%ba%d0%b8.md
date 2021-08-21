---
layout: post
title: Symfony2, Doctrine2, Postgresql и кодировки
date: 2015-06-15 18:19:52.000000000 +03:00
type: post
categories:
- Разработка
tags:
- pgsql
- php
- symfony2
permalink: "/2015/06/15/symfony2-doctrine2-postgresql-%d0%b8-%d0%ba%d0%be%d0%b4%d0%b8%d1%80%d0%be%d0%b2%d0%ba%d0%b8/"
---
Суть проблемы: в doctrine2 нет возможности выбрать кодировку подключения для драйвера pdo_pgsql. Совсем никак. Нет. Даже не пытайтесь. У вас ничего не получится.

Вот незадача: в mysql есть опция драйвера pdo [PDO::MYSQL_ATTR_INIT_COMMAND](http://php.net/manual/en/ref.pdo-mysql.php). Благодаря этой опции можно устанавливать кодировку подключения при помощи

```sql
set names 'utf8'
```

И даже драйвер mysql поддерживает установку кодировки при помощи опции charset в настройках подключения.

Если мы покопаемся в файле драйвера, то увидим, что кодировка исправно обрабатывается

```php
<b>Doctrine\DBAL\Driver\PDOMySql\Driver</b>

/**  
 * Constructs the MySql PDO DSN.  
 *  
 * @param array $params  
 *  
 * @return string The DSN.  
 */  
 private function _constructPdoDsn(array $params)  
 {  
 $dsn = 'mysql:';  
 if (isset($params['host']) && $params['host'] != '') {  
 $dsn .= 'host=' . $params['host'] . ';';  
 }  
 if (isset($params['port'])) {  
 $dsn .= 'port=' . $params['port'] . ';';  
 }  
 if (isset($params['dbname'])) {  
 $dsn .= 'dbname=' . $params['dbname'] . ';';  
 }  
 if (isset($params['unix_socket'])) {  
 $dsn .= 'unix_socket=' . $params['unix_socket'] . ';';  
 }  
 if (isset($params['charset'])) {  
 $dsn .= 'charset=' . $params['charset'] . ';';  
 }

return $dsn;  
 }
```

Для драйвера pdo_pgsql (Doctrine\DBAL\Driver\PDOPgSql\Driver) нет ничего подобного.

При этом сам [драйвер](http://www.postgresql.org/docs/8.4/static/multibyte.html) вполне успешно с кодировками работает.

Однако, безвыходных ситуаций не бывает. Чтобы как-то изменить кодировку при работе с базой pgsql можно применять [события symfony2](http://symfony.com/doc/current/cookbook/doctrine/event_listeners_subscribers.html). А конкретно событие postConnect из doctrine2.

Все, что нам потребуется - это реализовать собственный листенер этого события.

```php
namespace DatabaseBundle\Event\Listeners;

use Doctrine\DBAL\Event\ConnectionEventArgs;  
use Doctrine\DBAL\Events;  
use Doctrine\Common\EventSubscriber;

/**  
 * Событие инициализации подключения pgsql.  
 * Позволяет установить кодировку бд.  
 */  
class PgsqlConnectionInit implements EventSubscriber  
{  
 /**  
 * Используемая кодировка  
 *  
 * @var string  
 */  
 private $_charset;

/**  
 * Конфигурирование кодировки при создании класса  
 *  
 * @param string $charset The charset.  
 */  
 public function __construct($charset = 'utf8')  
 {  
 $this->_charset = $charset;  
 }

/**  
 * @param \Doctrine\DBAL\Event\ConnectionEventArgs $args  
 *  
 * @return void  
 */  
 public function postConnect(ConnectionEventArgs $args)  
 {  
 $args->getConnection()->executeQuery("SET NAMES ?", array($this->_charset));  
 }

/**  
 * {@inheritdoc}  
 */  
 public function getSubscribedEvents()  
 {  
 return array(Events::postConnect);  
 }  
}


```

А затем подключить этот эвент в config.yml

```
services:  
 pgsql.connection.init:  
 class: DatabaseBundle\Event\Listeners\PgsqlConnectionInit  
 tags:  
 - { name: doctrine.event_listener, event: postConnect }
```

Теперь все ок :)

