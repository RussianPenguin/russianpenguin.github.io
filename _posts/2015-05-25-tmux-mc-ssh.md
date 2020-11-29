---
layout: post
title: tmux + mc + ssh
date: 2015-05-25 13:03:05.000000000 +03:00
type: post
parent_id: '0'
published: true
password: ''
status: publish
categories:
- HowTo
tags:
- linux
meta:
  _wpcom_is_markdown: '1'
  sharing_disabled: '1'
  _rest_api_published: '1'
  _publicize_job_id: '10987472593'
  _rest_api_client_id: "-1"
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2015/05/25/tmux-mc-ssh/"
---
Если запустить ssh в tmux, а в нем запустить mc, то последний пожалуется, что тип терминала неизвестен и закроется. А на локальной машине не работают сочетания Shift+Fx.

Решение простое - добавить в файлы на удаленных машинах следующие опции (в локальные тоже не помешает):

**~/.profile**

```shell
if [$TERM = "screen"]; then  
 export TERM=xterm-color  
fi  
if [-n "$TMUX"]; then  
 export COLORTERM=rxvt  
fi
```

**~/.tmux.conf**

```
setw -g xterm-keys on
```

И задеплоить это с помощью [ansible](http://docs.ansible.com/index.html "Ansible Documentation").

