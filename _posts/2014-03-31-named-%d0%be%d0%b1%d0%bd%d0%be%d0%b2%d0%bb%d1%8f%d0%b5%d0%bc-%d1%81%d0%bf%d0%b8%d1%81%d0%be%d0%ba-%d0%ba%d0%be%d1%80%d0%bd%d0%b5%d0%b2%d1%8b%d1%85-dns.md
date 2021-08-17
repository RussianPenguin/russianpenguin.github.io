---
layout: post
title: 'Named: обновляем список корневых dns'
date: 2014-03-31 13:15:59.000000000 +04:00
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
  _publicize_pending: '1'
  _edit_last: '13696577'
  original_post_id: '151'
  _wp_old_slug: '151'
  _wpcom_is_markdown: '1'
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2014/03/31/named-%d0%be%d0%b1%d0%bd%d0%be%d0%b2%d0%bb%d1%8f%d0%b5%d0%bc-%d1%81%d0%bf%d0%b8%d1%81%d0%be%d0%ba-%d0%ba%d0%be%d1%80%d0%bd%d0%b5%d0%b2%d1%8b%d1%85-dns/"
---
Для Fedora/Red Hat

```bash; gutter: true; first-line: 1; highlight: []
dig +bufsize=1200 +norec NS . @a.root-servers.net > /var/named/named.ca
```
