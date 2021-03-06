---
layout: post
title: 'PulseAudio: Перенаправление потоков аля jackd'
date: 2020-10-11 00:48:48.000000000 +03:00
type: post
parent_id: '0'
published: true
password: ''
status: publish
categories:
- HowTo
tags:
- jackd
- linux
- pulseaudio
meta:
  _wpcom_is_markdown: '1'
  _thumbnail_id: '2567'
  _publicize_job_id: '49783101530'
  timeline_notification: '1602366532'
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2020/10/11/pulseaudio-loopback/"
excerpt: На простом примере рассмотрим как можно микшировать несколько источников
  звука в pulseaudio. Статья будет полезна так же и тем, кто любит стримить что-либо,
  но не имеет звуковой карты с модной loopback-функциональностью.
---

Что мы хотим? Мы хотим подмешать к микрофонному сигналу еще что-то. Например дополнительный сигнал с другой звуковой карты для того, чтобы по скайпу с кем-то поджемить на гитаре\ударке.

В jackd это сделать довольно просто - там куча графических роутеров. Но что делать если надо подмешать сигнал в pulse?

Возьмем самый простой пример: нужно подмешать к звуку микрофона аудио из браузера.

```
mic -> skype <- ff
```

Просто так это работать не будет. Потребуется виртуальное loopback-устройство, которое будет смешивать сигналы, а после остальное по будет использовать его как микрофон.

```
skype ^ |mic -> loopback <- ff
```

**1 - Создаем устройство**

Создаем само устройство и задаем ему описание чтобы в панели управления бло что-то вразумительное, а не "Пустой выход".

```
$ pacmd load-module module-null-sink sink\_name=fx-sink 42 $ pacmd update-sink-proplist fx-sink device.description=Виртуальный\_микрофон\_вход $ pacmd update-source-proplist fx-sink.monitor device.description=Виртуальный\_микрофон\_выход
```

Цифру, которая выдает первая команда можно запомнить. Это идентификатор, по которому можно sink удалить.

**2 - Перенаправляем микрофонный выход на это устройство**

```
$ pactl load-module module-loopback source=alsa\_input sink=fx-sink 43
```

Номер так же можно запомнить чтобы потом удалить ассоциацию.

Теперь в качестве устройства для ввода в панели управления (ставим как дефолтный девайс) или в программе выбираем fx-sink.monitor. Он будет называться "Виртуальный\_микрофон\_выход" (выше мы прописали в описании).

Чтобы посмотреть что писать в аргументе source просматриваем вывод команды

```
$ pactl list-sinks
```

Тут нам нужно найти содержимое поля name системного микрофона.


![](/assets/images/2020/10/2020-10-11-004003_select-1.png)

**Источники:**

- [ArchWiki: PulseAudio/Examples](https://wiki.archlinux.org/index.php/PulseAudio/Examples#Mixing_additional_audio_into_the_microphone's_audio)


