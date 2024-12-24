---
layout: post
title: 'GitLab: обновление бандлов ruby'
type: post
categories:
- HowTo
tags:
- git
- linux
- ruby
permalink: "/2015/09/21/gitlab-%d0%be%d0%b1%d0%bd%d0%be%d0%b2%d0%bb%d0%b5%d0%bd%d0%b8%d0%b5-%d0%b1%d0%b0%d0%bd%d0%b4%d0%bb%d0%be%d0%b2-ruby/"
---
![gitlab bundle update]({{ site.baseurl }}/assets/images/2015/09/gitlab.png){:.img-fluid}

После очередного "незапланированного" обновления у меня вдруг отвалился gitlab.  
В логах

```
/home/git/gitlab/vendor/bundle/ruby/2.1.0/gems/activesupport-4.1.9/lib/active_su  
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

Мануал по обновлению гитлаба: [https://gitlab.com/gitlab-org/gitlab-ce/blob/master/doc/update/patch_versions.md](https://gitlab.com/gitlab-org/gitlab-ce/blob/master/doc/update/patch_versions.md).

