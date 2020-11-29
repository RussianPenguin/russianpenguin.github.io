---
layout: post
title: 'Linux: Обрабатываем RAW-изображения'
date: 2019-07-28 17:17:57.000000000 +03:00
type: post
parent_id: '0'
published: true
password: ''
status: publish
categories:
- Обработка изображений
- HowTo
tags:
- консоль
- linux
- фотография
meta:
  _wpcom_is_markdown: '1'
  _rest_api_published: '1'
  _rest_api_client_id: "-1"
  timeline_notification: '1564324875'
  _publicize_job_id: '33364572634'
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2019/07/28/linux-%d0%be%d0%b1%d1%80%d0%b0%d0%b1%d0%b0%d1%82%d1%8b%d0%b2%d0%b0%d0%b5%d0%bc-raw-%d0%b8%d0%b7%d0%be%d0%b1%d1%80%d0%b0%d0%b6%d0%b5%d0%bd%d0%b8%d1%8f/"
excerpt: Рассмотрим основные инструменты для обработки raw-изображений. Так же посмотрим
  на метод стекинга изображения для получения качественной картинки.
---
![resize_result]({{ site.baseurl }}/assets/images/2019/07/resize_result.jpg?w=150)Мы не будем говорить о том, как правильно\неправильно обрабатывать raw'ки. Мы поговорим об инструментах (конечно же консольных), которые можно применять для конвертирования, склейки и других операций.

Фото на превью - это то, что удалось вытащить из raw (конечно же оно пожатое в джипег и отресайзено для публикации).

<!--more-->

Сначала немного о технике если хотим получить относительно хороший снимой:

- снимаем с брекетингом по экспозиции в 1-2 единицы в зависимости от освещения. Можно не угадать и засветить снимок. Тогда уже кадр не вытянуть. У большинства камер есть режим при котором они показывают засвеченные области. ![resize_2019-07-28 16-06-42.JPG]({{ site.baseurl }}/assets/images/2019/07/resize_2019-07-28-16-06-42.jpg)
- сохранение в жипег можно оставить чисто номинальное с низким разрешением и качеством (просто чтобы был если надо кому-то быстро-быстро показать на компе итоговый снимок).
- по-возможности включить 14-bit raw или больше - там можно вытянуть еще данных о цветности.

**Исходные данные**

Картинка, которую сохраняет фотик без предварительных обработок picture control (для canon оно называется иначе) совсем никуда не годится.

![resized_DSC3046.JPG]({{ site.baseurl }}/assets/images/2019/07/resized_dsc3046.jpg)

Режимы съемки: 1/200s, F9, 10.5mm, pc nl, wb sun.

Среди всех кадров в серии брекетинга это был нормальный. Без засветки.

**Инструменты**

- ufraw - позволяет преобразовывать raw в человеко-понятные форматы
- enfuse - склейка нескольких изображений с разной экспозицией для проявления высветления темных участков и затемнения светлых. Он же hdr.

**Процесс**

Для того, чтобы собрать снимок с хорошим качеством нам потребуется несколько исходников с разной экспозицией. Конечно же можно использовать брекетинг и готовые файлы с фотика пропустив конвертирование. А можно из одной равки сделать несколько кадров с экспокоррекцией на любое значение от -3 до 3 единиц.

[code lang=shell]$ ufraw --out-type=jpg --out-depth=8 --wb=camera --output=001.jpg --exposure=-0.33 --black-point=auto \_DSC3046.NEF  
$ ufraw --out-type=jpg --out-depth=8 --wb=camera --output=002.jpg --exposure=auto --black-point=auto \_DSC3046.NEF  
$ ufraw --out-type=jpg --out-depth=8 --wb=camera --output=003.jpg --exposure=0.33 --black-point=auto \_DSC3046.NEF
```

Важные параметры:

- --out-depth=8 - глубина цвета (зависит от формата сохранения), принимает значения 8 и 16;
- --wb=camera - конвертер пытается определить баланс белого по метаданным;
- --exposure=auto - коррекция экспозиции, auto\0 означает без коррекции, либо значение от -3 до 3;
- --black-point=auto - определение черной точки по метаданным.

Мы получили три кадра, которые в дальнейшем можно просуммировать и получить результат.

![2019-07-28-16:51:28_1129x1034.png]({{ site.baseurl }}/assets/images/2019/07/2019-07-28-165128_1129x1034.png)

А теперь их можно склеить в один кадр.

[code lang=shell]$ enfuse -o result.jpg --exposure-weight=0 --saturation-weight=0 --contrast-weight=1 --hard-mask \*.jpg
```

![resize_result.jpg]({{ site.baseurl }}/assets/images/2019/07/resize_result-1.jpg)

Результат гораздо симпатичнее.

**Литература**

- [Пингвин-фотолюбитель: 5. Стекинг](https://bs.shikhalev.org/2016/06/stacking.html)
- [Gimp: Blending Exposures](https://www.gimp.org/tutorials/Blending_Exposures/)
- [How to Improve Your Long Exposure Photography with Photo Stacking](https://digital-photography-school.com/how-to-improve-your-long-exposure-with-photo-stacking/)
