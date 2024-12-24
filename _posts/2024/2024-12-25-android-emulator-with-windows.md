---
layout: post
title: 'Android: Настройка android emulator в windows'
type: post
status: publish
categories:
- HowTo
tags:
- windows
- android
- virtualization
- bug
permalink: "/2024/12/25/android-emulator-with-windows"
---

Android Emulator, windows и встроенная видеокарта amd часто вызывают тонны головной боли.

- не устанавливается aehd
- не запускается виртуальная машина

Мы с вами рассмотрим обе эти проблемы и заодно поймём, почему больгинство мануалов в интернете не работаю и не решают проблемы.

## Не работает и не устанавливается aehd.

Aehd (он же android emulator hypervisor driver) нужен для аппаратного ускорения виртуальных машинх. Если у вас есть поддержка виртуализации в процессоре (а она скорее всего у вас есть, ведь за последние годы просто перестали производить cpu без подобных расширений), то она вам точно нужна.

Сначала ставим android sdk, а потом видим, что не поставился android emulator driver.

Можно пойти по [мануалу](https://github.com/google/android-emulator-hypervisor-driver?tab=readme-ov-file) и увидеть следующие ошибки в консоли:

```commandline
>silent_install.bat
[SC] ControlService FAILED 1062:

The service has not been started.

[SC] DeleteService SUCCESS
[SC] StartService FAILED with error 4294967201
```

Если кратко, то существуют два способа запуска эмулятора на винде.
1. Aehd. Он же android emulator hypervisor driver. Является форком kvm под винду
2. Hyper V. Он же компонент винды и платформа для виртуализации уже в маздае.

Чем же они принципиально отличаются и нужен ли вам первый или второй?

Первый нужен для всех версий ниже pro. Потому что в них нет гипер ви. А вот всё, что выше pro должно работать уже с нормальным гипервизором.

Поэтому если у вас первый вариант (а скорее всего у вас win >= pro :)), то смотрим в документацию.

А вот если у вас второй вариант, то надо обязательно поставить платформу hyper v из дополнительных компонентов windowa в панели управления.

Картинки [тут](https://android-developers.googleblog.com/2018/07/android-emulator-amd-processor-hyper-v.html).

## Не запускается эмулятор

А вот тут у вас скорее всего AMD и комбинация из встроенной и дискретной видеокарты.

Что можно сделать?

Идём в папку sdk с эмулятором.

```commandline
>cd %USERPROFILE%\AppData\Local\Android\Sdk\emulator
```

Смотрим, какие машинки созданы в андроид-студии.

```commandline
>emulator -list-avds
Medium_Phone_API_35
```

У меня это только `Medium_Phone_API_35`.

Пробуем её запустить и мониторим консоль на предмет ошибок. Скорее всего у вас тоже будет что-то такое как у меня.

```commandline
>emulator -avd Medium_Phone_API_35 -netdelay none -netspeed full -qt-hide-window -grpc-use-token -idle-grpc-timeout 0

...

←[0;39mandroid_startOpenglesRenderer: gpu infoGPU #1
  Make: 10de
  Model: NVIDIA GeForce GTX 1650 Ti
  Device ID: 1f95
I1224 02:58:38.110433    2788 HealthMonitor.cpp:279] HealthMonitor disabled.
I1224 02:58:38.110789    2788 VulkanDispatch.cpp:137] Added library: vulkan-1.dll
ERROR:             vkGetPhysicalDeviceProperties: Invalid physicalDevice [VUID-vkGetPhysicalDeviceProperties-physicalDevice-parameter]
```

И если мы видим что-то связанное с вулканом, то сначала пробуем отключить его через консоль. Добавляем параметр `-feature -Vulkan`.

```
>emulator -avd Medium_Phone_API_35 -netdelay none -netspeed full -qt-hide-window -grpc-use-token -idle-grpc-timeout 0 -feature -Vulkan
```

Эмулятор запустился скорее всего. Завершаем его по `ctrl-c`. И теперь надо сконфигурировать студию так, чтобы в эмуляторах она вулкан не использовала.

Не надо отключать автоматический выбор видеокарты как это делаю многие через задание перемённой `DISABLE_LAYER_AMD_SWITCHABLE_GRAPHICS_1`. Очень опрометчивое решение. Не рекомендую.

Редактируем файл `%USERPROFILE%\.android\advancedFeatures.ini` добавляя в него пару строк для отключения. Если файла нет, то надо его создать.

```
Vulkan = off
GLDirectMem = on
```

И, кстати, этот приём действует как для маздая, так и для линукса. Они оба подвержены проблеме с графиков.

Решится это может только обновлением драйвера для amd, что маловероятно.

Документация в которой ничего нет:
- https://developer.android.com/studio/run/emulator-troubleshooting?hl=ru
