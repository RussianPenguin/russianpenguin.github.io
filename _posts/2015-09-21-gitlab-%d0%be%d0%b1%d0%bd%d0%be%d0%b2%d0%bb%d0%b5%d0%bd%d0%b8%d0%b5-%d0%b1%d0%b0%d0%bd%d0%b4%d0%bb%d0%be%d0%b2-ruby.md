---
layout: post
title: 'GitLab: обновление бандлов ruby'
date: 2015-09-21 20:58:56.000000000 +03:00
type: post
parent_id: '0'
published: true
password: ''
status: publish
categories:
- HowTo
tags:
- git
- linux
- ruby
meta:
  _wpcom_is_markdown: '1'
  sharing_disabled: '1'
  _rest_api_published: '1'
  _rest_api_client_id: "-1"
  _publicize_job_id: '15021558786'
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2015/09/21/gitlab-%d0%be%d0%b1%d0%bd%d0%be%d0%b2%d0%bb%d0%b5%d0%bd%d0%b8%d0%b5-%d0%b1%d0%b0%d0%bd%d0%b4%d0%bb%d0%be%d0%b2-ruby/"
---
[![gitlab bundle update]({{ site.baseurl }}/assets/images/2015/09/gitlab.png?w=150)](https://russianpenguin.files.wordpress.com/2015/09/gitlab.png)После очередного "незапланированного" обновления у меня вдруг отвалился gitlab.  
В логах

```
/home/git/gitlab/vendor/bundle/ruby/2.1.0/gems/activesupport-4.1.9/lib/active\_su  
pport/dependencies.rb:247:in `require': Incorrect MySQL client library version!  
This gem was compiled for 5.5.42 but the client library is 5.6.25. (RuntimeError  
)
```

Надо обновлять.  
Беда в том, что в ruby я не нашел аналога pip update или что-то в таком духе.

Для обновления предустановленных бандлов нужно удалить старые и поставить новые (заново).

```shell
$ sudo -u git mv /home/git/gitlab/vendor/bundle{,.bkp}
```

```shell
sudo -u git -H bundle install --without development test postgres --deployment
```

Мануал по обновлению гитлаба: [https://gitlab.com/gitlab-org/gitlab-ce/blob/master/doc/update/patch\_versions.md](https://gitlab.com/gitlab-org/gitlab-ce/blob/master/doc/update/patch_versions.md).

