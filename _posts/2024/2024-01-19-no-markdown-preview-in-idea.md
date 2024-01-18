---
layout: post
title: 'IDEA: Не работает превью для Markdown'
type: post
status: publish
categories:
- HowTo
tags:
- linux
- idea
- java
permalink: "/2024/01/09/no-markdown-preview-in-idea"
---

<img class="kdpv" src="{{ site.baseurl }}/assets/images/2024/markdown_idea/1.png" alt="There are no available preview providers" title="There are no available preview providers" />

Есть вероятность, что возможность смотреть превью markdown-файлов в соответствующем [плагине](https://www.jetbrains.com/help/idea/markdown.html) отсутствует.

Скорее всего проблема в jdk и jfx, которые вы используете. Чаще всего используются те, что идут бандлом с самое ide.

Нужно поменять:
- идем в быстрый поиск (двойное нажатие shift)
- вбиваем _choose boot java runtime for ide_
- выбираем другую версию jdk c jcef
- плагин должен заработать корректно
- если не случилось - повторяем (можно использовать системный jre)

<img class="center" src="{{ site.baseurl }}/assets/images/2024/markdown_idea/2.png" alt="Choose JRE" title="Choose JRD" />

<img class="center" src="{{ site.baseurl }}/assets/images/2024/markdown_idea/3.png" alt="Choose new version" title="Choose new version" />

<img class="center" src="{{ site.baseurl }}/assets/images/2024/markdown_idea/4.png" alt="Done" title="Done" />
