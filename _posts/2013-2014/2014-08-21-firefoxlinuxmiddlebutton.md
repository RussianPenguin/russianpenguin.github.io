---
layout: post
title: Firefox+Linux+MiddleButton
date: 2014-08-21 14:36:13.000000000 +04:00
type: post
categories:
- HowTo
tags:
- firefox
- linux
permalink: "/2014/08/21/firefoxlinuxmiddlebutton/"
---
Есть такая особенность у сборки Firefox в Linux (как под виндой - не знаю) - если нажать среднюю кнопку (не на ссылке, а просто в пределах страницы), то в адресную строку будет вставлено содержимое буфера обмена.

Очень и очень раздражающая фича.

Но отключается она очень просто.

1. Идем в [about:config](config "about:config").
2. Находим настройку **middlemouse.contentLoadURL** и выставляем ее в **false** (или создаем - это параметр "логическое")

Вопреки советам в сети за подобное поведение параметр **middlemouse.paste** не отвечает.

