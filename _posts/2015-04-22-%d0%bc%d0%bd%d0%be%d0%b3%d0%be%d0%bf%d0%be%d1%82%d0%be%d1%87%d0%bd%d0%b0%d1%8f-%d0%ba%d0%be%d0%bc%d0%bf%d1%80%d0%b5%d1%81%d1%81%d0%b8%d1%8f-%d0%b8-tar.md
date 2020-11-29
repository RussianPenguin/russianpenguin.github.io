---
layout: post
title: Многопоточная компрессия и tar
date: 2015-04-22 21:00:57.000000000 +03:00
type: post
parent_id: '0'
published: true
password: ''
status: publish
categories:
- HowTo
tags:
- bash
- linux
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
permalink: "/2015/04/22/%d0%bc%d0%bd%d0%be%d0%b3%d0%be%d0%bf%d0%be%d1%82%d0%be%d1%87%d0%bd%d0%b0%d1%8f-%d0%ba%d0%be%d0%bc%d0%bf%d1%80%d0%b5%d1%81%d1%81%d0%b8%d1%8f-%d0%b8-tar/"
---
Сколько можно? :) Ядер все больше и больше, а

[code lang="shell"]$ tar -cjf /mnt/\_backup/`date '+%Y-%m-%d_%H-%M-%S'`.tbz2 /home[/code]

как работал в один поток, так и работает.

Есть два многопоточных решения:

- pbzip2 - параллельный bzip
- pigz - параллельный gzip

[code lang="shell"]$ tar -c /home | pbzip2 -vc -9 -p7 /mnt/\_backup/`date '+%Y-%m-%d_%H-%M-%S'`.tbz2[/code]

Опцией -p# можно управлять количеством ядер, которые будет использовать архиватор. Нормально - это N-1.

Аналогично и для gzip

[code lang="shell"]$ tar -c /home | pigz -vc -9 -p7 /mnt/\_backup/`date '+%Y-%m-%d_%H-%M-%S'`.tbz2[/code]

С удивлением открыл для себя, что - в bash по дефолту означает stdout. И

[code lang="shell"]$ tar -cf - file[/code]

будет использовать в качестве файла stdout. :)

