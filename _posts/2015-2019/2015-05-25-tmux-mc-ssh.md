---
layout: post
title: tmux + mc + ssh
type: post
categories:
- HowTo
tags:
- linux
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

