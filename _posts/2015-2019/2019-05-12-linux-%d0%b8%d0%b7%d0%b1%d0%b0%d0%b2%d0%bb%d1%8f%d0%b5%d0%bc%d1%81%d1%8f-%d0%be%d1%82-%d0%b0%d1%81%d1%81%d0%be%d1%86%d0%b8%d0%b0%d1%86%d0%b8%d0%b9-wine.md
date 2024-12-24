---
layout: post
title: 'Linux: Избавляемся от файловых ассоциаций WINE'
type: post
categories:
- HowTo
- linux
tags:
- wine
permalink: "/2019/05/12/linux-%d0%b8%d0%b7%d0%b1%d0%b0%d0%b2%d0%bb%d1%8f%d0%b5%d0%bc%d1%81%d1%8f-%d0%be%d1%82-%d0%b0%d1%81%d1%81%d0%be%d1%86%d0%b8%d0%b0%d1%86%d0%b8%d0%b9-wine/"
excerpt: Быстрый способ избавиться от файловых ассоциаций wine.
---
При работе с wine постоянно наблюдается появление новых файловых ассоциаций, которые только мешают.  
Ожин из способов удалить эти ассоциации -  
то удалить соответствующие mime-типы (есть другой способ - просто отучить wine их создавать, но там возникает проблема с тем, то он перестает создавать ярлыки приложений).  
```shell
$ rm -f ~/.local/share/applications/mimeinfo.cache  
$ rm -f ~/.local/share/mime/packages/x-wine*  
$ rm -f ~/.local/share/mime/application/x-wine-extension-*  
$ update-desktop-database ~/.local/share/applications
```

