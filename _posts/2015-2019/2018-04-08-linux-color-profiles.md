---
layout: post
title: Управление цветом в Linux
type: post
categories:
- HowTo
tags:
- icc
- linux
- цвет
- selinux
permalink: "/2018/04/08/%d1%83%d0%bf%d1%80%d0%b0%d0%b2%d0%bb%d0%b5%d0%bd%d0%b8%d0%b5-%d1%86%d0%b2%d0%b5%d1%82%d0%be%d0%bc-%d0%b2-linux/"
excerpt: "Не секрет, что для качественной печати на цветных принтерах необходимо выполнить
  множество действий по установке цветовых профилей. \nВ статье рассматривается как
  происходит назначение профилей icc/icm различным устройствам графического ввода-вывода
  в nix-подобных операционных системах."
---
![01-intro]({{ site.baseurl }}/assets/images/2018/04/01-intro.jpg){:.img-fluid}

Есть такая штука под названием "уветовые профили". Казалось бы все о них слышали, но немногие умеют их использовать и понимают, зачем это вообще надо.

То, что мы воспринимаем как свет - это лишь электромагнитные колебания с длиной волны от 380-400 нм до 760-780 нм. В этом диапазоне смешались все цвета от красного к филетовому.

![02-spectrum]({{ site.baseurl }}/assets/images/2018/04/02-spectrum.png){:.img-fluid}

[https://ru.wikipedia.org/wiki/Свет](https://ru.wikipedia.org/wiki/%D0%A1%D0%B2%D0%B5%D1%82)

Когда мы видим радугу, то мы видим весь спектр цветов. А вот несовершенные электронные приборы могут отобразить лишь определенные цвета радуги. Называется это цветовым охватом - множество доступных для восприятия человеческим глазом цветов, которые способно воспроизвести устройство. У всей техники разные диаграммы цветопередачи.

Именно поэтому для каждого устройства в системе нужно задать цветовой профиль, который как раз и описывает диапазон цветового охвата.

Почему это важно? Можно пояснить на примере двух инженеров у одного из которых линейка метрическая, а у второго дюймовая. Если один другому скажет, что нужно начертить линию длиной два, то случится нечто странное: линия будет иметь длину два, но в той системе измерений, в которой работает человек.

То же самое с техникой. Если для нас цвето RGB(200, 0, 0) - это красный с каким-то уровнем насыщенности, то для принтера он может быть совершенно другим. Поэтому перед печатью все должно быть сконвертировано с учетом цветового профили устройства.

Подводя итог: цветовой профиль - это правила конвертации цвета из общепризнанной цветовой модели в ту, с которой работает ваша техника.

Когда мы просто печатаем текст на компьютере, то нас не сильно волнует, как это се конвертируется, но когда при печати фотографии мы получим цвета, которых на экране не видели, то пора что-то менять. :)

<!--more-->

## Управление цветом.

### Предварительная настройка

**Описываемые ниже шаги рассказывают о том,как реализованы подобные настройки на нижнем уровне. Если вы используете DE, в которых есть механизмы управления мониторами, то нижеописанные действия по настройке вам не нужны.**

Всю работу по настройке связки устройство-профиль в никсах берет на себя демон [colord](https://www.freedesktop.org/software/colord/intro.html). Именно он управляет базой данных цветовых профилей.

Будем рассматривать как это работает на примере Fedora GNU/Linux.

Если демона нет в списке юнитов, то его надо поставить и добавить в список автоматически-запускаемых (В большинстве сборок федоры все уже сделано до вас и эти шаги не требуются).

```shell
$ sudo dnf install colord colord-libs  
$ sudo systemctl enable colord  
$ sudo systemctl start colord
```

Проверим, что сервис установлен.

```shell
$ sudo systemctl list-units
```

Для gnome и kde все уже сделано и в панели управления есть подраздел управления цветовыми профилями, но для lxde (xmonad, i3, etc.) нужно добавить менеджер управления цветом ибо встроенных средств у этих средств нету.

Единственным на сегодня механизмом является демон [xiccd](https://github.com/agalakhov/xiccd "github: alakhov/xiccd"). Компилить его из исходников нет надобности - можно поставить через [corp](https://copr.fedorainfracloud.org/coprs/dschubert/xiccd/ "Fedora Corp: dschubert/xiccd").

```shell
$ sudo dnf copr enable dschubert/xiccd  
$ sudo dnf install xiccd
```

После установки нужно перезайти в систему (софт прописывается в автостарт автоматически), либо запустить xiccd руками.

Проверить правильность работы можно командой

```shell
$ colormgr get-devices
```

Если все хорошо, то среди устройств должен быть ваш монитор.

Можно посмотреть переменную _ICC_PROFILE (именно она хранит интересующие нас данные).

```shell
$ xprop -display :0.0 -len 14 -root _ICC_PROFILE
```

Вывод будет выглядить как-то так:

```
_ICC_PROFILE(CARDINAL) = 0, 0, 2, 252, 108, 99, 109, 115, 4, 48, 0, 0, 109, 110  

```

У иксов есть [баг](https://bugs.debian.org/cgi-bin/bugreport.cgi?bug=851810 "xcalib: Error - unsupported ramp size 0"), который не позволяет устанавливать профили монитора для интеловских видеокарт,

Решается принудительным прописыванием информации о драйвере в конфиг иксов /etc/X11/xorg.conf.d/20-intel.conf.

```
Section "Device"  
 Identifier "Intel Graphics"  
 Driver "intel"  
EndSection
```

Если этого не сделать, то при попытке применить профиль к монитору мы получим сообщение об ошибке.

```
$ xcalib -d :0 ~/.local/share/icc/S273HL.ICM  
Error - unsupported ramp size 0
```

### Монитор(ы)

Для удобства нам потребуется поставить систему управления agryllcms - в ней есть удобная команда dispwin.

```shell
$ sudo dnf install agryllcms*
```

Не будем затрагивать процесс калибровки - это тема хорошо расписана в [вики](https://wiki.archlinux.org/index.php/ICC_profiles "ICC profiles") арча.

В первую очередь нужно добыть цветовые профили для своих мониторов и принтеров (и сканеров). Их можно скачать на сайте производителя или найти в интернете на сайтах подобных [tftcentral](http://www.tftcentral.co.uk/articles/icc_profiles.htm#the_database "tftcentral").

Для добавления файла в базу вводим простую команду импорта.

```shell
$ colormgr import-profile file.icc
```

Если все прошло хорошо, то команда выдаст нам несколько строк, среди которых будет Profile ID. Её нужно запомнить так как именно по этому идентификатору мы будем ассоциировать файл с оборудованием.

```
Profile ID: icc-7b1f4835daffceb605fd8378367c1200
```

Повторяем так для каждого скачанного профиля. Все они будет импортированы в каталог ~/.local/share/icc.

Теперь нужно найти монитор при помощи команды

```shell
$ colormgr get-devices
```

На экране будет отображен список устройств и нам нужно запомнить параметр device id для каждого монитора.

```
Device ID: xrandr-Acer Technologies-S273HL-LQA0C0028000
```

Ассоциирование устройства и профиля выполняется командой

```shell
$ colormgr device-add-profile "device_id" "profile_id"
```

А установка профиля по-умолчанию

```shell
$ colormgr device-make-profile-default "device_id" "profile_id"
```

Применить профиль без перезагрузки машины можно командой

```
$ dispwin -d 
```

Номер монитора можно узнать в справке dispwin.

```
$ dispwin -h  
...  
 -d n[,m] Choose the display n from the following list (default 1)  
 Optionally choose different display m for Video LUT access  
 1 = 'Screen 1, Output eDP1 at 0, 1080, width 1920, height 1080'  
 2 = 'Screen 2, Output HDMI1 at 0, 0, width 1920, height 1080'  
...
```

Важно отметить, что далеко не все профили от производителей идут с заполненными lut-таблицами (это таблицы кривых rgb, который загружаются в память видиокарты).

Если таблицы нет, что при попытке применить профиль мы увидим сообщение

```
Dispwin: Warning - No vcgt tag found in profile - assuming linear
```

Это есть настройки гаммы, которую всегда можно осуществить вручную.

С мониторами разобрались. При следующей загрузке будут применены корректные профили.

### Принтер(ы)

С принтерами все несколько сложнее. Не все поддерживают демон настроек colord. Gutenprint поддерживает :). От него и будем отталкиваться (да и большинство hp при растеризации используют цветовые профили).

Как вообще это происходит: изображение отправляется на печать, а cups выполняет растеризацию и преобразование изображения в нужное цветовое пространство (настройки самого пространства берутся из colord).

И тут кроется один нюанс. Выполним просмотр доступных профилей при помощи colormgr.

```
$ colormgr get-profiles  
...  
System Wide: No  
...
```

У всех мы видим строку с характеристикой system wide. Настройки принтера обязаны быть system wide иначе он просто откажется работать.

А для этого нам нужно положить все icc\icm в /usr/share/color/icc/ (можно и нужно создать подкаталог, например printerProfiles). А затем на все дать права чтения и установить владельца root.

После этого ничего работать не будет. А в логах мы найде сообщения об ошибке.

```
]: true .putdeviceprops --nostringval-- unknownerror  
]: Operand stack:  
]: Unrecoverable error: undefined in .putdeviceprops  
]: | ./base/gsicc_manage.c:1799: gsicc_set_device_profile(): cannot find device profile  
]: ./base/gsicc_manage.c:1148: gsicc_open_search(): Could not find /usr/share/color/icc/user/XP600-premium-glossy-water-based.icc
```

Но при этом файл будет лежать по указанному пути. Все дело в контексте SeLinux. Посмотрим внимательнее на содержимое каталога.

```shell
$ ls -lZ  
итого 456  
drwxr-xr-x. 2 root root system_u:object_r:usr_t:s0 4096 апр 7 23:17 basICColor  
drwxr-xr-x. 2 root root system_u:object_r:usr_t:s0 4096 апр 7 23:10 colord  
drwxr-xr-x. 2 root root system_u:object_r:usr_t:s0 4096 апр 7 23:17 lcms  
drwxr-xr-x. 2 root root system_u:object_r:usr_t:s0 4096 апр 7 23:17 OpenICC  
drwxr-xr-x. 2 root root system_u:object_r:usr_t:s0 4096 апр 7 23:17 Oyranos  
drwxr-xr-x. 2 root root unconfined_u:object_r:usr_t:s0 4096 апр 7 23:53 user  
-rw-r--r--. 1 root root unconfined_u:object_r:user_home_t:s0 441740 дек 4 11:31 XP330.icm
```

Было добавлен файл XP330.icm. Мы видим, что контекст файла сильно отличается от того, что у всех остальных. Это и есть причина, по которой cups (а конкретно gutenprint) не может открыть профиль.

Поправим это при помощи restorecon.

```shell
$ restorecon -Rv .  
Relabeled /usr/share/color/icc/XP330.icm from unconfined_u:object_r:user_home_t:s0 to unconfined_u:object_r:usr_t:s0
```

Поправили и теперь нужно перезагрузить colord.

```shell
$ sudo systemctl restart colord
```

Выполнив команду colormgr get-profiles мы увидим среди всех зарегистрированнх icc нащ, который положили выше. И у него будет установлен признак system wide, что и требовалось.

В выводе устройств colormgr get-devices находим наш принтер, связываем его и icc.

```shell
$ colormgr device-add-profile "device_id" "profile_id"  
$ colormgr device-make-profile-default "device_id" "profile_id"
```

Можно печатать.

## Литература

- [https://www.freedesktop.org/software/colord/intro.html](https://www.freedesktop.org/software/colord/intro.html)
- [https://ru.wikipedia.org/wiki/Свет](https://ru.wikipedia.org/wiki/Свет)
- [https://ru.wikipedia.org/wiki/Цветовая_модель](https://ru.wikipedia.org/wiki/Цветовая_модель)
- [https://wiki.archlinux.org/index.php/ICC_profiles](https://wiki.archlinux.org/index.php/ICC_profiles)
