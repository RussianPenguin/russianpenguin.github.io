---
layout: post
title: 'Named: обновляем список корневых dns'
type: post
categories:
- HowTo
tags:
- linux
permalink: "/2014/03/31/named-%d0%be%d0%b1%d0%bd%d0%be%d0%b2%d0%bb%d1%8f%d0%b5%d0%bc-%d1%81%d0%bf%d0%b8%d1%81%d0%be%d0%ba-%d0%ba%d0%be%d1%80%d0%bd%d0%b5%d0%b2%d1%8b%d1%85-dns/"
---
Для Fedora/Red Hat

```bash
dig +bufsize=1200 +norec NS . @a.root-servers.net > /var/named/named.ca
```
