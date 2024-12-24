---
layout: post
title: Изменение размера vmdk-диска
type: post
categories:
- HowTo
tags:
- virtualbox
permalink: "/2019/05/09/%d0%b8%d0%b7%d0%bc%d0%b5%d0%bd%d0%b5%d0%bd%d0%b8%d0%b5-%d1%80%d0%b0%d0%b7%d0%bc%d0%b5%d1%80%d0%b0-vmdk-%d0%b4%d0%b8%d1%81%d0%ba%d0%b0/"
excerpt: Диски в формате vmdk от virtualbox не умеют менять размер одной командой.
  Описывается прием для их масштабирования.
---
Диски vmdk не поддерживают простое изменение размера. Нужно сначала конверировать в vdi, изменять размер и конвертировать обратно.

Опция размера передается в мегабайтах

```shell
$ VBoxManage clonehd "source.vmdk" "cloned.vdi" --format vdi  
$ VBoxManage modifyhd "cloned.vdi" --resize 51200  
$ VBoxManage clonehd "cloned.vdi" "resized.vmdk" --format vmdk
```

