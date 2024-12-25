---
layout: post
title: Ошибка юнита systemd-modules-load.service
type: post
categories:
- linux
tags:
- secureboot
- systemd
- uefi
permalink: "/2020/04/28/%d0%be%d1%88%d0%b8%d0%b1%d0%ba%d0%b0-%d1%8e%d0%bd%d0%b8%d1%82%d0%b0-systemd-modules-load-service/"
excerpt: Узнаем почему не загружается vboxdrv и починим.
---
![2020-04-28-22:47:01_select]({{ site.baseurl }}/assets/images/2020/04/2020-04-28-224701_select.png){:.img-fluid}

```
Ошибка юнита systemd-modules-load.service
```

Видели такое при старте системы и не можете понять в чем причина?

А приина в secureboot. Один из модулей ядра у вас не имеет цифровой подписи.

Чаще всего это происходит с модулями, которые собираются через akmod. Среди них virtualbox и nvidia (вроде amd тоже, но у меня нет возможности проверить). В последних релизах федоры что-то сломали и модули больше не подписываются при сборке.

```
where: suplibOsInit what: 3 VERR_VM_DRIVER_NOT_INSTALLED (-1908) - The support driver is not installed. On linux, open returned ENOENT.
```

И что делать? Можно отключить secureboot?

Можно, но не нужно. Будете страдать от сообщения о несекурной загрузке.

1. Создаем собственный ключ для подписи модулей и каталог, в котором это будет жить.

```
# mkdir /root/module-signing  
# cd /root/module-signing  
# openssl req -new -x509 -newkey rsa:2048 -keyout MOK.priv -outform DER -out MOK.der -nodes -days 36500 -subj "/CN=YOUR_NAME/"  
# chmod 600 MOK.priv
```

1. Регистрируем ключ в системе при помощи mokutils. Нам потребуется задать простой одноразовый пароль, который у нас спросят при перезагрузке.

```
# mokutil --import /root/module-signing/MOK.der  
input password:  
input password again:
```

1. Перезагружаемся. Выбираем Enroll MOK. После выбираем единственный ключ и вводим пароль из шага выше.
2. Пишем скрипт для подписания модулей. Помимо виртуалбокса туда можно добавить нужные вам записи.

```
#!/bin/bash

for modfile in $(dirname $(modinfo -n vboxdrv))/*.ko; do  
 echo "Signing $modfile"  
 /usr/src/kernels/$(uname -r)/scripts/sign-file sha256 \  
 /root/module-signing/MOK.priv \  
 /root/module-signing/MOK.der "$modfile"  
done
```

1. После обновления ядра\модуля\компонента запускаем скрипт выше, а потом перезагружаем модули.

```
# ./root/module-signing/sign-vbox-modules  
Signing /lib/modules/5.6.6-200.fc31.x86_64/extra/VirtualBox/vboxdrv.ko  
Signing /lib/modules/5.6.6-200.fc31.x86_64/extra/VirtualBox/vboxnetadp.ko  
Signing /lib/modules/5.6.6-200.fc31.x86_64/extra/VirtualBox/vboxnetflt.ko  
# systemctl restart systemd-modules-load.service  

```

[Источник](https://stegard.net/2016/10/virtualbox-secure-boot-ubuntu-fail/)

