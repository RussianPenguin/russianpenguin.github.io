---
layout: post
title: Разработка на Python с использованием virtualenv
date: 2015-04-05 18:31:34.000000000 +03:00
type: post
categories:
- Разработка
- HowTo
tags:
- python
permalink: "/2015/04/05/%d1%80%d0%b0%d0%b7%d1%80%d0%b0%d0%b1%d0%be%d1%82%d0%ba%d0%b0-%d0%bd%d0%b0-python-%d1%81-%d0%b8%d1%81%d0%bf%d0%be%d0%bb%d1%8c%d0%b7%d0%be%d0%b2%d0%b0%d0%bd%d0%b8%d0%b5%d0%bc-virtualenv/"
---
[Virtual Environment](https://virtualenv.readthedocs.org/en/latest/ "virtualenv") - полезный инструмент, который позволет держать различные конфигурации зависимостей проектов в разных директориях. Например он решает проблему когда одному приложению требуется версия 1.0 пакета X, а другому - 2.0.

## vitrualenv

Главным инструментов будет virtualenv.  
```shell
$ sudo yum install python-virtualenv
```

### Использование

Создадим окружение

```shell
$ cd project_dir  
$ virtualenv proj_env
```

Этими командами мы создадим папку proj_env, которая будет содержать наше новое окружение. В этой папке будет набор скриптов и копия интерпретатора python, который будет использоваться в окружении (окружение использует свой интерпретатор, а не общесистемный).

При создании окружения можно указать, какая версия python нам нужна.

Возможно, что в системе параллельно стоит как python2, так и python3. Выбирать версию при создании окружения мы можем ключем -p. Если ключ не указан, то будет выбрана версия /usr/bin/python.

```shell
$ virtualenv -p /usr/bin/python3.4 proj_env
```

Для того, чтобы попасть в наше новое окружение используем

```shell
$ source proj_env/bin/activate
```

Теперь мы внутри окружения.

Определеить это можно по изменившемуся приглашению:

```
(proj_env)тут_старое_приглашение_из_$PS1
```

Все. Мы внутри окружения. Можно ставить зависимости для нашего приложения.

```shell
$ pip install flask flask-bootsrap rq rq-scheduler pymysql
```

Выйти из окружения можно при помощи

```shell
$ deactivate
```

Теперь мы попали обратно в систему с дефолтными интерпретаторами.

### Сохранение информации о зависимостях

Опция --no-site-packages отключает использование глобально-установленных пакетов, что может быть полезно (сейчас это дефолтное поведение virtualenv).

Хорошей идей будет сохранить информацию об установленных в окружении пакетов.

{code lang="shell"]$ pip freeze > requirements.txt
```

После развертывание окружения в новой системе можно поднять все пакеты сразу.

```shell
$ pip install -r requirements.txt
```

Не забываем добавить папку окружения в .gitignore.

## virtualenverapper

Еще один полезный инструмент (скорее для разработчиков, а не для деплоя), который позволяет обращаться со со множеством окружений и переключаться между ними.

```shell
$ sudo yum install python-virtualenvwrapper
```

### Применение

Добавляем в .bashrc

```shell
export WORKON_HOME=~/.envs  
source /usr/bin/virtualenvwrapper.sh
```

Теперь можно создавать окружения.

```shell
$ mkvirtualenv proj_env
```

Активировать окружения.

```shell
$ workon proj_env
```

Выходить из окружения можно так же как это делалось в кготом virtualenv.

```shell
$ deactivate
```

Удалять окружения.

```shell
$ rmvirtualenv proj_env
```

При этом все папки окружений будет расположены в одном месте: папке, которая задана через $WORKON_HOME.

### Дополнительные команды

Есть несколько дополнительных команд

- lsvirtualenv - покажет список созданных окружений
- cdvirtualenv - перейдет непосрественно в папку окружения
- cdsitepackages - переведет в site-packages выбранного окруженияч
- lssitepackages - сделает ls для папки site-packages

[Посмотреть полный список команд](http://virtualenvwrapper.readthedocs.org/en/latest/command_ref.html "Список команд virtualenvwrapper").

## autoenv

[Утилита](https://github.com/kennethreitz/autoenv "autoenv") позволяет активировать окружение при входе в папку, и деактивировать при выходе.

```shell
$ git clone git://github.com/kennethreitz/autoenv.git ~/.autoenv  
$ echo 'source ~/.autoenv/activate.sh' >> ~/.bashrc
```

