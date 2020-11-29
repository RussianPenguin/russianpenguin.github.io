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
<!-- wp:paragraph -->

Что мы хотим? Мы хотим подмешать к микрофонному сигналу еще что-то. Например дополнительный сигнал с другой звуковой карты для того, чтобы по скайпу с кем-то поджемить на гитаре\ударке.

<!-- /wp:paragraph -->

<!-- wp:paragraph -->

В jackd это сделать довольно просто - там куча графических роутеров. Но что делать если надо подмешать сигнал в pulse?

<!-- /wp:paragraph -->

<!-- wp:paragraph -->

Возьмем самый простой пример: нужно подмешать к звуку микрофона аудио из браузера.

<!-- /wp:paragraph -->

<!-- wp:code -->

```
mic -> skype <- ff
```

<!-- /wp:code -->

<!-- wp:paragraph -->

Просто так это работать не будет. Потребуется виртуальное loopback-устройство, которое будет смешивать сигналы, а после остальное по будет использовать его как микрофон.

<!-- /wp:paragraph -->

<!-- wp:code -->

```
skype ^ |mic -> loopback <- ff
```

<!-- /wp:code -->

<!-- wp:paragraph -->

**1 - Создаем устройство**

<!-- /wp:paragraph -->

<!-- wp:paragraph -->

Создаем само устройство и задаем ему описание чтобы в панели управления бло что-то вразумительное, а не "Пустой выход".

<!-- /wp:paragraph -->

<!-- wp:syntaxhighlighter/code {"language":"bash"} -->

```
$ pacmd load-module module-null-sink sink\_name=fx-sink 42 $ pacmd update-sink-proplist fx-sink device.description=Виртуальный\_микрофон\_вход $ pacmd update-source-proplist fx-sink.monitor device.description=Виртуальный\_микрофон\_выход
```

<!-- /wp:syntaxhighlighter/code -->

<!-- wp:paragraph -->

Цифру, которая выдает первая команда можно запомнить. Это идентификатор, по которому можно sink удалить.

<!-- /wp:paragraph -->

<!-- wp:paragraph -->

**2 - Перенаправляем микрофонный выход на это устройство**

<!-- /wp:paragraph -->

<!-- wp:syntaxhighlighter/code {"language":"bash"} -->

```
$ pactl load-module module-loopback source=alsa\_input sink=fx-sink 43
```

<!-- /wp:syntaxhighlighter/code -->

<!-- wp:paragraph -->

Номер так же можно запомнить чтобы потом удалить ассоциацию.

<!-- /wp:paragraph -->

<!-- wp:paragraph -->

Теперь в качестве устройства для ввода в панели управления (ставим как дефолтный девайс) или в программе выбираем fx-sink.monitor. Он будет называться "Виртуальный\_микрофон\_выход" (выше мы прописали в описании).

<!-- /wp:paragraph -->

<!-- wp:paragraph -->

Чтобы посмотреть что писать в аргументе source просматриваем вывод команды

<!-- /wp:paragraph -->

<!-- wp:syntaxhighlighter/code -->

```
$ pactl list-sinks
```

<!-- /wp:syntaxhighlighter/code -->

<!-- wp:paragraph -->

Тут нам нужно найти содержимое поля name системного микрофона.

<!-- /wp:paragraph -->

<!-- wp:image {"align":"center","id":2581,"sizeSlug":"large","linkDestination":"media"} -->

<figure class="aligncenter size-large"><a href="https://russianpenguin.files.wordpress.com/2020/10/2020-10-11-004003_select-1.png"><img src="%7B%7B%20site.baseurl%20%7D%7D/assets/images/2020/10/2020-10-11-004003_select-1.png?w=1024" alt="" class="wp-image-2581"></a></figure>

<!-- /wp:image -->

<!-- wp:paragraph -->

**Источники:**

<!-- /wp:paragraph -->

<!-- wp:list -->

- [ArchWiki: PulseAudio/Examples](https://wiki.archlinux.org/index.php/PulseAudio/Examples#Mixing_additional_audio_into_the_microphone's_audio)

<!-- /wp:list -->

<!-- wp:paragraph -->

<!-- /wp:paragraph -->

