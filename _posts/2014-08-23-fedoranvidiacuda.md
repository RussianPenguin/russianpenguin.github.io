---
layout: post
title: Fedora+Nvidia=CUDA
date: 2014-08-23 18:22:16.000000000 +04:00
type: post
parent_id: '0'
published: true
password: ''
status: publish
categories:
- HowTo
tags:
- cuda
- linux
- nvidia
meta:
  _wpcom_is_markdown: '1'
  sharing_disabled: '1'
  _wpas_skip_facebook: '1'
  _wpas_skip_google_plus: '1'
  _wpas_skip_twitter: '1'
  _wpas_skip_linkedin: '1'
  _wpas_skip_tumblr: '1'
  _wpas_skip_path: '1'
  _publicize_pending: '1'
  _rest_api_published: '1'
  _rest_api_client_id: "-1"
  _edit_last: '13696577'
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2014/08/23/fedoranvidiacuda/"
---
Итак. Мы хотим получить рабочую инсталляцию на Fedora (в моем случае - RFRemix и с этим связано несколько багов).

Для этого нам надо:

1. видеокарту nvidia :)
2. установленный репозитарий cuda ([отсюда](https://developer.nvidia.com/cuda-downloads "CUDA Toolkit downloads"))
3. [заметку о том, как восстановить plymouth после установки драйвером nvidia](http://www.if-not-true-then-false.com/2014/fedora-20-nvidia-guide/ "Fedora 20 nVidia Drivers Install / Uninstall / Restore Plymouth")

**1 - Ставим**

```shell
# yum install cuda
```

Этот метапакет поставит все, что должно идти в комплекте (включая свежие дрова)

**2 - Удаляем kmod-nvidia**

Если у вас уже были проинсталены дрова невидии, то нужно удалить имеющийся kmod. Так как он может содержать несовместимую с текущими библиотеками версию модуля

```shell
# yum remove kmod-nvidia
```

**3 - Пересобираем akmod**

```shell
# akmod -kernel $(uname -r)
```

Тут может потребоваться доставить kernel-headers

**4 - Пересобираем initrd**

Для этого воспользуемся dracut

```shell
# dracut --force
```

**5 - Ребут :)**

**6 - Можно собрать семплы**

Замечу, что делать это надо под рутом или проставив соотвествующие права на папки. Так как мейкфайлы сильно завязаны на относительные пути.

```shell
# cd /usr/local/cuda-6.5/samples/5_Simulations/fluidsGL  
# make
```

**7 - А вот тут нас поджидает облом (RFRemix)**

```shell
>>> WARNING - libGL.so not found, refer to CUDA Samples release notes for how to find and install them. <<<  
>>> WARNING - libGLU.so not found, refer to CUDA Samples release notes for how to find and install them. <<<  
>>> WARNING - libX11.so not found, refer to CUDA Samples release notes for how to find and install them. <<<  
>>> WARNING - libXi.so not found, refer to CUDA Samples release notes for how to find and install them. <<<  
>>> WARNING - libXmu.so not found, refer to CUDA Samples release notes for how to find and install them. <<<
```

Ага!

Смотрим в _findgllib.mk_

```shell
ifeq ("$(OSLOWER)","linux")  
 # first search lsb_release  
 DISTRO = $(shell lsb_release -i -s 2>/dev/null | tr "[:upper:]" "[:lower:]")  
 DISTVER = $(shell lsb_release -r -s 2>/dev/null)  
 ifeq ("$(DISTRO)","")  
 # second search and parse /etc/issue  
 DISTRO = $(shell more /etc/issue | awk '{print $$1}' | sed '1!d' | sed -e "/^$$/d" 2>/dev/null | tr "[:upper:]" "[:lower:]")  
 DISTVER= $(shell more /etc/issue | awk '{print $$2}' | sed '1!d' 2>/dev/null  
 endif  
 ifeq ("$(DISTRO)","")  
 # third, we can search in /etc/os-release or /etc/{distro}-release  
 DISTRO = $(shell awk '/ID/' /etc/*-release | sed 's/ID=//' | grep -v "VERSION" | grep -v "ID" | grep -v "DISTRIB")  
 DISTVER= $(shell awk '/DISTRIB_RELEASE/' /etc/*-release | sed 's/DISTRIB_RELEASE=//' | grep -v "DISTRIB_RELEASE")  
 endif  
endif

ifeq ("$(OSUPPER)","LINUX")  
 # $(info) >> findgllib.mk -> LINUX path <<<)  
 # Each set of Linux Distros have different paths for where to find their OpenGL libraries reside  
 UBUNTU_PKG_NAME = "nvidia-340"  
 UBUNTU = $(shell echo $(DISTRO) | grep -i ubuntu >/dev/null 2>&1; echo $$?)  
 FEDORA = $(shell echo $(DISTRO) | grep -i rfremix >/dev/null 2>&1; echo $$?)  
 RHEL = $(shell echo $(DISTRO) | grep -i red >/dev/null 2>&1; echo $$?)  
 CENTOS = $(shell echo $(DISTRO) | grep -i centos >/dev/null 2>&1; echo $$?)  
 SUSE = $(shell echo $(DISTRO) | grep -i suse >/dev/null 2>&1; echo $$?)
```

Еще раз ага!

Смотрим как определяется тип дистрибутива.

Это команда

```shell
awk '/ID/' /etc/*-release | sed 's/ID=//' | grep -v "VERSION" | grep -v "ID" | grep -v "DISTRIB"
```

А у меня она выдает _rfremix_. А значит такого таргета ни разу нет в списке. :)  
Ну что делать-то? меняем _fedora_ на _rfremix_ в _findgllib.mk_ и радуемся.  
Собралось! Но...

**8 - А что у нас с библиотеками?**

```shell
$ ./fluidsGL  
./fluidsGL: error while loading shared libraries: libcufft.so.6.5: cannot open shared object file: No such file or directory
```

Еще раз. У нас для ldconfig не заданы пути, где лежит _libcufft.so.6.5_

```shell
$ find /usr/ -name libcufft.so.6.5  
/usr/local/cuda-6.5/targets/x86_64-linux/lib/libcufft.so.6.5
```

```shell
$ grep -R /usr/local/cuda-6.5/targets/x86_64-linux/lib /etc/ld.so.conf.d/
```

Пусто.

```shell
# echo "/usr/local/cuda-6.5/targets/x86_64-linux/lib" > /etc/ld.so.conf.d/cuda-lib64.conf  
# ldconfig
```

**9 - Наслаждаемся**

[![fluidsGL]({{ site.baseurl }}/assets/images/2014/08/d0b2d18bd0b4d0b5d0bbd0b5d0bdd0b8d0b5_055.png)](/2014/08/d0b2d18bd0b4d0b5d0bbd0b5d0bdd0b8d0b5_055.png)

