---
layout: post
title: Разработка на Python с использованием virtualenv
date: 2015-04-05 18:31:34.000000000 +03:00
type: post
parent_id: '0'
published: true
password: ''
status: publish
categories:
- Разработка
- HowTo
tags:
- python
meta:
  _wpcom_is_markdown: '1'
  sharing_disabled: '1'
  _publicize_pending: '1'
  _rest_api_published: '1'
  _rest_api_client_id: "-1"
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2015/04/05/%d1%80%d0%b0%d0%b7%d1%80%d0%b0%d0%b1%d0%be%d1%82%d0%ba%d0%b0-%d0%bd%d0%b0-python-%d1%81-%d0%b8%d1%81%d0%bf%d0%be%d0%bb%d1%8c%d0%b7%d0%be%d0%b2%d0%b0%d0%bd%d0%b8%d0%b5%d0%bc-virtualenv/"
---
[Virtual Environment](https://virtualenv.readthedocs.org/en/latest/ "virtualenv") - полезный инструмент, который позволет держать различные конфигурации зависимостей проектов в разных директориях. Например он решает проблему когда одному приложению требуется версия 1.0 пакета X, а другому - 2.0.

## vitrualenv

Главным инструментов будет virtualenv.  
[code lang="shell"]$ sudo yum install python-virtualenv[/code]

### Использование

Создадим окружение

[code lang="shell"]$ cd project\_dir  
$ virtualenv proj\_env[/code]

Этими командами мы создадим папку proj\_env, которая будет содержать наше новое окружение. В этой папке будет набор скриптов и копия интерпретатора python, который будет использоваться в окружении (окружение использует свой интерпретатор, а не общесистемный).

При создании окружения можно указать, какая версия python нам нужна.

Возможно, что в системе параллельно стоит как python2, так и python3. Выбирать версию при создании окружения мы можем ключем -p. Если ключ не указан, то будет выбрана версия /usr/bin/python.

[code lang="shell"]$ virtualenv -p /usr/bin/python3.4 proj\_env[/code]

Для того, чтобы попасть в наше новое окружение используем

[code lang="shell"]$ source proj\_env/bin/activate[/code]

Теперь мы внутри окружения.

Определеить это можно по изменившемуся приглашению:

[code](proj\_env)тут\_старое\_приглашение\_из\_$PS1[/code]

Все. Мы внутри окружения. Можно ставить зависимости для нашего приложения.

[code lang="shell"]$ pip install flask flask-bootsrap rq rq-scheduler pymysql[/code]

Выйти из окружения можно при помощи

[code lang="shell"]$ deactivate[/code]

Теперь мы попали обратно в систему с дефолтными интерпретаторами.

### Сохранение информации о зависимостях

Опция --no-site-packages отключает использование глобально-установленных пакетов, что может быть полезно (сейчас это дефолтное поведение virtualenv).

Хорошей идей будет сохранить информацию об установленных в окружении пакетов.

{code lang="shell"]$ pip freeze \> requirements.txt[/code]

После развертывание окружения в новой системе можно поднять все пакеты сразу.

[code lang="shell"]$ pip install -r requirements.txt[/code]

Не забываем добавить папку окружения в .gitignore.

## virtualenverapper

Еще один полезный инструмент (скорее для разработчиков, а не для деплоя), который позволяет обращаться со со множеством окружений и переключаться между ними.

[code lang="shell"]$ sudo yum install python-virtualenvwrapper[/code]

### Применение

Добавляем в .bashrc

[code lang="shell"]export WORKON\_HOME=~/.envs  
source /usr/bin/virtualenvwrapper.sh[/code]

Теперь можно создавать окружения.

[code lang="shell"]$ mkvirtualenv proj\_env[/code]

Активировать окружения.

[code lang="shell"]$ workon proj\_env[/code]

Выходить из окружения можно так же как это делалось в кготом virtualenv.

[code lang="shell"]$ deactivate[/code]

Удалять окружения.

[code lang="shell"]$ rmvirtualenv proj\_env[/code]

При этом все папки окружений будет расположены в одном месте: папке, которая задана через $WORKON\_HOME.

### Дополнительные команды

Есть несколько дополнительных команд

- lsvirtualenv - покажет список созданных окружений
- cdvirtualenv - перейдет непосрественно в папку окружения
- cdsitepackages - переведет в site-packages выбранного окруженияч
- lssitepackages - сделает ls для папки site-packages

[Посмотреть полный список команд](http://virtualenvwrapper.readthedocs.org/en/latest/command_ref.html "Список команд virtualenvwrapper").

## autoenv

[Утилита](https://github.com/kennethreitz/autoenv "autoenv") позволяет активировать окружение при входе в папку, и деактивировать при выходе.

[code lang="shell"]$ git clone git://github.com/kennethreitz/autoenv.git ~/.autoenv  
$ echo 'source ~/.autoenv/activate.sh' \>\> ~/.bashrc[/code]

