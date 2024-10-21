---
layout: post
title: 'KDE: Dolphin не может создать файл на nfs, а консоль может'
type: post
status: publish
categories:
- HowTo
- Баги
tags:
- kde
- console
- network
- linux
permalink: "/2024/10/21/dolphin-nfs-bug"
---

Итак. Существует проблема в связке nfs+dolphin. Настолько большая, что многие пользователи попросту не используют nfs.

Она заключается в том, что если на каталоге установлено сопоставление с анонимным пользователем для всех (через `all_squash`), то в ряде случаев пользователи просто не могут создавать каталоги и файлы из dolphin (где-то видел упоминание о том, что nautilus так же поломан). Только через консоль (либо альтернативные менеджеры)

Проблема имеет лишь несколько веток на реддите вида [Dolphin Can't Edit Synology NFS Folders Even When User Has Permissions?](https://www.reddit.com/r/kde/comments/n55jp9/dolphin_cant_edit_synology_nfs_folders_even_when/)

Хотя и обычный nfs подвержен таким же проблемам.

Рассматривать будем на примере nas от synology.

Данное явление распространяется как на nfs3, так и на nfs4. Последнюю использовать дома смысла нет - её преимущества раскрываются только в домене.

На записи ниже хорошо видно как проявляет себя проблема.

<video width="100%" controls preload="metadata">
  <source src="{{ site.baseurl }}/assets/images/2024/dolphin_nfs_bug/6a89f6427a95b3150c86e822a8d5521d_MD5.webm" type="video/webm">
</video>

Казалось бы. Пользователь сопоставляется с админом. На каталоге есть права для пользователя. Что ещё надо?
```bash
# cat /etc/exports  
/volume1/projects  192.168.1.0/24(rw,async,no_wdelay,all_squash,insecure_locks,sec=sys,anonuid=1024,anongid=100)
```

```bash
$ ls -ld misc/  
drwxrwxr-x. 3 nas-writer users 4096 окт 21 23:14 misc/  
$ ls -ld misc/Новая\ папка/  
drwxr-xr-x. 2 nas-writer users 4096 окт 21 23:18 'misc/Новая папка/'  
```

Попробуем добавить прав как это сделано с родительским каталогом.

```bash
$ sudo chmod g+w misc/Новая\ папка/
$ ls -ld misc/Новая\ папка/  
drwxrwxr-x. 2 nas-writer users 4096 окт 21 23:18 'misc/Новая папка/'
```

И опять ничего не выходит:

![Ничего не получилось]({{ site.baseurl }}/assets/images/2024/dolphin_nfs_bug/9ef60aee72f95a00b4d0572c324f03f4_MD5.jpeg)

И даже если добавить права на всех (`a+w`), то мы тоже не сможем создавать файлы и каталоги из файлового менеджера.

```bash
$ sudo chmod a+w misc/Новая\ папка/  
$ ls -ld misc/Новая\ папка/  
drwxrwxrwx. 2 nas-writer users 4096 окт 21 23:18 'misc/Новая папка/'
```

Корни проблемы:
- в первую очередь проблема в KIO и KDE (там есть баг аж от 2015 года).
- во вторую очередь - это чехарда с правами на самой станции (которая иногда случается когда файлы копируются туда-сюда или рсинкаются).

Давайте посмотрим на то, что происходит на станции.

Разрешения папки misc.

![Разрешения папки misc]({{ site.baseurl }}/assets/images/2024/dolphin_nfs_bug/3b4174822f49599395ff1c4eaeda13f1_MD5.jpeg)

И разрешение папки `Новая папка`.

![Разрешения новой папки]({{ site.baseurl }}/assets/images/2024/dolphin_nfs_bug/167ea6afd0aa0b3483849767bccba834_MD5.jpeg)

Очевидно, что они наследуются. Разрешение на запись для админа выглядит так:

![Админские права]({{ site.baseurl }}/assets/images/2024/dolphin_nfs_bug/bab914c1278a76acf958f859b71744a9_MD5.jpeg)

Однако, если мы поставим `a+w` на папку misc, то dolphin сможет создавать файлы внутри нового каталога. И мы можем даже убрать права `o-w` на новую папку! И всё равно файлы создавать можно.

```bash
$ sudo chmod a+w misc  
$ ls -ld misc/  
drwxrwxrwx. 4 nas-writer users 4096 окт 21 23:18 misc/  
$ sudo chmod o-w misc/Новая\ папка/  
$ ls -ld misc/Новая\ папка/  
drwxrwxr-x. 2 nas-writer users 4096 окт 21 23:18 'misc/Новая папка/'
```

![Пробуем починить]({{ site.baseurl }}/assets/images/2024/dolphin_nfs_bug/4e8b8b8d77aaa524b593353a2961e26d_MD5.jpeg)

И я даже знать не хочу почему это работает.

Теперь мы можем всё починить. Так как при создании папок права наследуются от родительского объекта, то нам нужно для группы администраторов (а в данном случае это именно так и есть) дать полный доступ на родительский каталог и на все существующие в данным момент папки.

Для этого установим администраторов единственной группой с полным доступом к файлам.

Для этого в файловом менеджере на станции выбираем экспортируемый каталог и на вкладке разрешений устанавливаем следующие права.

Обязательно выставляем флажок для применения ко вложенным объектам.

![Чиним нормально]({{ site.baseurl }}/assets/images/2024/dolphin_nfs_bug/e145101c2fc3154aa78d225d00cc1842_MD5.jpeg)

Стоит отметить еще один эффект: теперь все файлы и папки создаются с правами 777.

<video width="100%" controls preload="metadata">
  <source src="{{ site.baseurl }}/assets/images/2024/dolphin_nfs_bug/90442a9b1f85f16c8f94794f4a35abea_MD5.webm" type="video/webm">
</video>

Даже если они создаются через smb-шару.

Речь идет только о шарах с полным доступом для всех пользователей. Т.н. файлопомойки. Задача таких мест - это позволять любому пользователю писать и удалять любые объекты. В статье мы не рассматриваем случай с авторизованными пользователями - там всё и так хорошо.

Вообще стоит объяснить почему так происходит.

В данном случае применяется не обычные права доступа, а расширенный acl. Он настраивается таким образом, что дочерние объекты наследуют пермиссии родительских.

Давайте посмотрим на примере.

```bash
$ mkdir test  
$ ls -la  
итого 0  
drwxr-xr-x. 1 penguin penguin   8 окт 22 00:11 .  
drwx------. 1 penguin penguin 810 окт 22 00:05 ..  
drwxr-xr-x. 1 penguin penguin   0 окт 22 00:11 test  
$ cd test/  
$ mkdir test_2  
$ ls -la  
итого 0  
drwxr-xr-x. 1 penguin penguin 12 окт 22 00:11 .  
drwxr-xr-x. 1 penguin penguin  8 окт 22 00:11 ..  
drwxr-xr-x. 1 penguin penguin  0 окт 22 00:11 test_2  
$ cd ..  
$ chmod g+w test/  
$ cd test/  
$ mkdir test_3  
$ ls -la  
итого 0  
drwxrwxr-x. 1 penguin penguin 24 окт 22 00:11 .  
drwxr-xr-x. 1 penguin penguin  8 окт 22 00:11 ..  
drwxr-xr-x. 1 penguin penguin  0 окт 22 00:11 test_2  
drwxr-xr-x. 1 penguin penguin  0 окт 22 00:11 test_3
```

Видим, что изменение прав на родительский каталог никак не повлияло на создание дочернего каталога `test_3`. Потому что права подчиняются установленной в система `umask`, `dmask` и `fmask`.

А теперь то же самое, но с acl.

```bash
$ mkdir test  
$ ls -la  
итого 0  
drwxr-xr-x. 1 penguin penguin   8 окт 22 00:16 .  
drwx------. 1 penguin penguin 810 окт 22 00:05 ..  
drwxr-xr-x. 1 penguin penguin   0 окт 22 00:16 test  
$ cd test/  
$ mkdir test_2  
$ ls -la  
итого 0  
drwxr-xr-x. 1 penguin penguin 12 окт 22 00:16 .  
drwxr-xr-x. 1 penguin penguin  8 окт 22 00:16 ..  
drwxr-xr-x. 1 penguin penguin  0 окт 22 00:16 test_2  
$ cd ../  
$ setfacl --recursive --modify u:penguin:rwX,g:penguin:rwX,d:g:penguin:rwX,d:u:penguin:rwX test  
$ ls -la  
итого 0  
drwxr-xr-x. 1 penguin penguin   8 окт 22 00:16 .  
drwx------. 1 penguin penguin 810 окт 22 00:05 ..  
drwxrwxr-x+ 1 penguin penguin  12 окт 22 00:16 test  
$ getfacl test/  
# file: test/  
# owner: penguin  
# group: penguin  
user::rwx  
user:penguin:rwx  
group::r-x  
group:penguin:rwx  
mask::rwx  
other::r-x  
default:user::rwx  
default:user:penguin:rwx  
default:group::r-x  
default:group:penguin:rwx  
default:mask::rwx  
default:other::r-x  
  
$ cd test/  
$ ls -la  
итого 0  
drwxrwxr-x+ 1 penguin penguin 12 окт 22 00:16 .  
drwxr-xr-x. 1 penguin penguin  8 окт 22 00:16 ..  
drwxrwxr-x+ 1 penguin penguin  0 окт 22 00:16 test_2  
$ mkdir test_3  
$ ls -la  
итого 0  
drwxrwxr-x+ 1 penguin penguin 24 окт 22 00:18 .  
drwxr-xr-x. 1 penguin penguin  8 окт 22 00:16 ..  
drwxrwxr-x+ 1 penguin penguin  0 окт 22 00:16 test_2  
drwxrwxr-x+ 1 penguin penguin  0 окт 22 00:18 test_3
```

В этом и кроется способ починки nfs и dolphin.

И еще стоит обратить внимание, что acl для nfs4 отличается. И именно поэтому стоит использовать nfs3 если у вас нет домена.