---
layout: post
title: 'PDF: уменьшаем размер файла'
type: post
status: publish
categories:
- HowTo
tags:
- shell
- linux
- pdf
permalink: "/2022/06/27/redude_pdf_size"
---

<img class="img-fluid" src="{{ site.baseurl }}/assets/images/2022/pdf-sizes.png" alt="сравнение занимаемого места" title="Сравнение размеров файлов" />

```shell
gs \
  -sDEVICE=pdfwrite \
  -dCompatibilityLevel=1.4 \
  -dPDFSETTINGS=/ebook \
  -dNOPAUSE \
  -dQUIET \
  -dBATCH \
  -sOutputFile=doc_compress.pdf \
  doc.pdf
```

Основная опция тут ```-dPDFSETTINGS=/ebook```. Именна она указывает ghostscript жать пдф до состояния, которое пригодно для просмотра в читалках.

А чаще всего такая задача появляется когда кучу отсканированных страниц документа нужно согнать в один pdf.

