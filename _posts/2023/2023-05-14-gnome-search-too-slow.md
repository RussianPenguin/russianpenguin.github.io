---
layout: post
title: 'Gnome: очень медленный диалог поиска'
type: post
status: publish
categories:
- HowTo
tags:
- shell
- linux
- gnome
- network
permalink: "/2023/05/14/gnome-search-too-slow"
---

<img class="img-fluid" src="{{ site.baseurl }}/assets/images/2023/gnome-search-too-slow.png" alt="сравнение занимаемого места" title="Поиск очень медленный" />

Поиск по строке в gnome shell очень медленный. 

Чтобы найти причину открываем wireshark и смотрим, что происходит на интерфейсах в момент ввода текста в поле поиска.

![Результат мониторинга сети]({{ site.baseurl }}/assets/images/2023/gnome-search-too-slow-wireshark.png){:.img-fluid}

В момент ввода текста наблюдаем шквал обращений к шаре nfs (в моем случае).

Чтобы этого избежать достаточно занести все пути в игнор у tracker.

* Ставим dconf;
* Редактируем ключ ```org.freedesktop.tracker.miner.files.ignored-directories```;
* Моментально все обращения к шаре пропадают.

В вашем случае может быть иначе. Однако, большинство проблем tracker'а связаны с медленной сетью.

![dconf]({{ site.baseurl }}/assets/images/2023/gnome-search-too-slow-dconf.png){:.img-fluid}