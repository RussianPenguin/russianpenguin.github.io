---
layout: post
title: PyCharm заставляем terminal работать с virtualenv
date: 2015-09-08 23:00:36.000000000 +03:00
type: post
categories:
- HowTo
tags:
- ide
permalink: "/2015/09/08/pycharm-%d0%b7%d0%b0%d1%81%d1%82%d0%b0%d0%b2%d0%bb%d1%8f%d0%b5%d0%bc-terminal-%d1%80%d0%b0%d0%b1%d0%be%d1%82%d0%b0%d1%82%d1%8c-%d1%81-virtualenv/"
---
[![pycharm terminal with virtualenv]({{ site.baseurl }}/assets/images/2015/09/pycharm-terminal.png)](/2015/09/pycharm-terminal.png) Создаем виртуальное окружение как указано в [доке](https://www.jetbrains.com/pycharm/help/creating-virtual-environment.html).  
Путь к окружению должен выглядеть как  
```
<путь к проекту>/.venv
```

И не забудьте добавить эту папку в .gitignore.

Теперь в корне проекта создаем файл .pycharmrc

```
source ~/.bashrc  
source .venv/bin/activate
```

Осталось добавить запуск нашего окружения в настройках **Tools** -> **Terminal**.

Прописываем свойство **Shell path** как

```
/bin/bash --rcfile .pycharmrc
```

