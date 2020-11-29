---
layout: post
title: 'Linux: произвольное падение приложений на mono'
date: 2017-06-05 14:57:33.000000000 +03:00
type: post
parent_id: '0'
published: true
password: ''
status: publish
categories: []
tags:
- linux
- mono
- repetierhost
meta:
  _wpcom_is_markdown: '1'
  _rest_api_published: '1'
  _rest_api_client_id: "-1"
  _publicize_job_id: '5794247843'
author:
  login: russianpenguin
  email: maksim.v.zubkov@gmail.com
  display_name: russianpenguin
  first_name: Maksim
  last_name: Zubkov
permalink: "/2017/06/05/linux-%d0%bf%d1%80%d0%be%d0%b8%d0%b7%d0%b2%d0%be%d0%bb%d1%8c%d0%bd%d0%be%d0%b5-%d0%bf%d0%b0%d0%b4%d0%b5%d0%bd%d0%b8%d0%b5-%d0%bf%d1%80%d0%b8%d0%bb%d0%be%d0%b6%d0%b5%d0%bd%d0%b8%d0%b9-mono/"
excerpt: Столкнулся с неочевидной проблемой - раз за разом закрывался RepetierHost
  и не давал напечатать модель на принтере.
---
![53395]({{ site.baseurl }}/assets/images/2017/06/53395.png?w=150)Столкнулся со странной проблемой: без сторонней помощи стал закрываться RepetierHost. Ему было все равно печатал он модель, слайсил или просто был открытым. Сам хост я всегда открывал на отдельном воркспейсе и поначалу грешил на то, что какие-то проблемы могут быть у cinnamon, который сейчас использую как дефолтный стол. Если оставить хост отрытым и не менять рабочие столы, не запускать никаких приложений, то все нормально и приложение не отваливалось. Гугл ничего не смог сказать мне про систематический вылеты. Откат на предыдушие версии не помог - они так же вылетали.

Добавил в repetierHost строки, которые перенаправляли лог запуска в файл и он стал выглядеть вот так.

[code lang="shell"]#!/usr/bin/env bash  
cd /home/penguin/.soft/RepetierHost  
env LANG=en\_US.utf8 mono RepetierHost.exe -home {$HOME}.soft/RepetierHost &\> {$HOME}/log&[/code]

После пары запусков я таки поймал в лог креш приложения. Да, не обращаете внимание на то, что принудительно используется локаль en\_US - это решает проблему слайсера Slic3r, который по каким-то причинам отказывается работать с группами объектов названными символами, отличными от латинских.

[code]/usr/bin/slic3r --load "slic3r\_settings.ini" --print-center 100.00,100.00 -o "composition.gcode" "composition.amf"  
Wide character at /usr/lib64/perl5/vendor\_perl/Encode.pm line 212.[/code]

В amf-файле все группы должны иметь латинские имена, а repetier генерирует имена в текущей локали.

Пойманный трейс выглядит следующим образом.

[code]Stacktrace:

at \<unknown\> \<0xffffffff\>  
at (wrapper managed-to-native) System.Windows.Forms.X11Keyboard.Xutf8LookupString (intptr,System.Windows.Forms.XEvent&,byte[],int,intptr&,System.Windows.Forms.XLookupStatus&) \<0x000a4\>  
at System.Windows.Forms.X11Keyboard.LookupString (System.Windows.Forms.XEvent&,int,System.Windows.Forms.XKeySym&,System.Windows.Forms.XLookupStatus&) \<0x000bb\>  
at System.Windows.Forms.X11Keyboard.EventToVkey (System.Windows.Forms.XEvent) \<0x0003f\>  
at System.Windows.Forms.X11Keyboard.ToUnicode (int,int,string&) \<0x0034f\>  
at System.Windows.Forms.X11Keyboard.TranslateMessage (System.Windows.Forms.MSG&) \<0x0011f\>  
at System.Windows.Forms.XplatUIX11.TranslateMessage (System.Windows.Forms.MSG&) \<0x00027\>  
at System.Windows.Forms.XplatUI.TranslateMessage (System.Windows.Forms.MSG&) \<0x00024\>  
at System.Windows.Forms.Application.RunLoop (bool,System.Windows.Forms.ApplicationContext) \<0x00d6b\>  
at System.Windows.Forms.Application.Run (System.Windows.Forms.ApplicationContext) \<0x00047\>  
at System.Windows.Forms.Application.Run (System.Windows.Forms.Form) \<0x00037\>  
at RepetierHost.Program.Main (string[]) \<0x0003f\>  
at (wrapper runtime-invoke) \<Module\>.runtime\_invoke\_void\_object (object,intptr,intptr,intptr) \<0x000d1\>

Native stacktrace:

mono(+0xc3794) [0x55d10cc73794]  
mono(+0x11a8ce) [0x55d10ccca8ce]  
mono(+0x3c633) [0x55d10cbec633]  
/lib64/libpthread.so.0(+0x115c0) [0x7f4b23cea5c0]  
/lib64/libc.so.6(strlen+0x26) [0x7f4b23788fe6]  
/lib64/libX11.so.6(\_XimLocalUtf8LookupString+0xde) [0x7f4b1870b8fe]  
[0x4249f8b5]

Debug info from gdb:

[New LWP 5108]  
[New LWP 5112]  
[New LWP 5116]  
[New LWP 5117]  
[New LWP 5118]  
[New LWP 5119]  
[New LWP 5121]  
[New LWP 5122]  
[New LWP 5123]  
warning: File "/usr/bin/mono-sgen-gdb.py" auto-loading has been declined by your `auto-load safe-path' set to "$debugdir:$datadir/auto-load".  
To enable execution of this file add  
add-auto-load-safe-path /usr/bin/mono-sgen-gdb.py  
line to your configuration file "/home/penguin/.gdbinit".  
To completely disable this security protection add  
set auto-load safe-path /  
line to your configuration file "/home/penguin/.gdbinit".  
For more information about this security protection see the  
"Auto-loading safe path" section in the GDB manual.&nbsp; E.g., run from the shell:  
info "(gdb)Auto-loading safe path"  
warning: File "/usr/bin/mono-sgen-gdb.py" auto-loading has been declined by your `auto-load safe-path' set to "$debugdir:$datadir/auto-load".  
[Thread debugging using libthread\_db enabled]  
Using host libthread\_db library "/lib64/libthread\_db.so.1".  
0x00007f4b23ce9fdb in waitpid () from /lib64/libpthread.so.0  
Id&nbsp;&nbsp; Target Id&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Frame  
\* 1&nbsp;&nbsp;&nbsp; Thread 0x7f4b247fa780 (LWP 5104) "mono" 0x00007f4b23ce9fdb in waitpid () from /lib64/libpthread.so.0  
2&nbsp;&nbsp;&nbsp; Thread 0x7f4b1c3ff700 (LWP 5108) "mono" 0x00007f4b23ce6460 in pthread\_cond\_wait@@GLIBC\_2.3.2 () from /lib64/libpthread.so.0  
3&nbsp;&nbsp;&nbsp; Thread 0x7f4b1cb62700 (LWP 5112) "Finalizer" 0x00007f4b23ce8957 in do\_futex\_wait.constprop () from /lib64/libpthread.so.0  
4&nbsp;&nbsp;&nbsp; Thread 0x7f4b0c301700 (LWP 5116) "Timer-Scheduler" 0x00007f4b23ce6809 in pthread\_cond\_timedwait@@GLIBC\_2.3.2 () from /lib64/libpthread.so.0  
5&nbsp;&nbsp;&nbsp; Thread 0x7f4b18044700 (LWP 5117) "Timer-Scheduler" 0x00007f4b23ce6809 in pthread\_cond\_timedwait@@GLIBC\_2.3.2 () from /lib64/libpthread.so.0  
6&nbsp;&nbsp;&nbsp; Thread 0x7f4b06eef700 (LWP 5118) "Threadpool work" 0x00007f4b23ce6809 in pthread\_cond\_timedwait@@GLIBC\_2.3.2 () from /lib64/libpthread.so.0  
7&nbsp;&nbsp;&nbsp; Thread 0x7f4b06cee700 (LWP 5119) "Threadpool work" 0x00007f4b23ce6809 in pthread\_cond\_timedwait@@GLIBC\_2.3.2 () from /lib64/libpthread.so.0  
8&nbsp;&nbsp;&nbsp; Thread 0x7f4b05af4700 (LWP 5121) "Threadpool work" 0x00007f4b23ce6809 in pthread\_cond\_timedwait@@GLIBC\_2.3.2 () from /lib64/libpthread.so.0  
9&nbsp;&nbsp;&nbsp; Thread 0x7f4b06241700 (LWP 5122) "Threadpool work" 0x00007f4b237f801d in poll () from /lib64/libc.so.6  
10&nbsp;&nbsp; Thread 0x7f4b04c93700 (LWP 5123) "Threadpool work" 0x00007f4b23ce6809 in pthread\_cond\_timedwait@@GLIBC\_2.3.2 () from /lib64/libpthread.so.0

Thread 10 (Thread 0x7f4b04c93700 (LWP 5123)):  
#0&nbsp; 0x00007f4b23ce6809 in pthread\_cond\_timedwait@@GLIBC\_2.3.2 () at /lib64/libpthread.so.0  
#1&nbsp; 0x000055d10cd6b9ab in worker\_thread ()  
#2&nbsp; 0x000055d10cd661d6 in start\_wrapper ()  
#3&nbsp; 0x000055d10ce1af4a in inner\_start\_thread ()  
#4&nbsp; 0x00007f4b23ce06ca in start\_thread () at /lib64/libpthread.so.0  
#5&nbsp; 0x00007f4b23803f7f in clone () at /lib64/libc.so.6

Thread 9 (Thread 0x7f4b06241700 (LWP 5122)):  
#0&nbsp; 0x00007f4b237f801d in poll () at /lib64/libc.so.6  
#1&nbsp; 0x000055d10cd6d2f2 in poll\_event\_wait ()  
#2&nbsp; 0x000055d10cd6e136 in selector\_thread ()  
#3&nbsp; 0x000055d10cd661d6 in start\_wrapper ()  
#4&nbsp; 0x000055d10ce1af4a in inner\_start\_thread ()  
#5&nbsp; 0x00007f4b23ce06ca in start\_thread () at /lib64/libpthread.so.0  
#6&nbsp; 0x00007f4b23803f7f in clone () at /lib64/libc.so.6

Thread 8 (Thread 0x7f4b05af4700 (LWP 5121)):  
#0&nbsp; 0x00007f4b23ce6809 in pthread\_cond\_timedwait@@GLIBC\_2.3.2 () at /lib64/libpthread.so.0  
#1&nbsp; 0x000055d10cd6b9ab in worker\_thread ()  
#2&nbsp; 0x000055d10cd661d6 in start\_wrapper ()  
#3&nbsp; 0x000055d10ce1af4a in inner\_start\_thread ()  
#4&nbsp; 0x00007f4b23ce06ca in start\_thread () at /lib64/libpthread.so.0  
#5&nbsp; 0x00007f4b23803f7f in clone () at /lib64/libc.so.6

Thread 7 (Thread 0x7f4b06cee700 (LWP 5119)):  
#0&nbsp; 0x00007f4b23ce6809 in pthread\_cond\_timedwait@@GLIBC\_2.3.2 () at /lib64/libpthread.so.0  
#1&nbsp; 0x000055d10cd6b9ab in worker\_thread ()  
#2&nbsp; 0x000055d10cd661d6 in start\_wrapper ()  
#3&nbsp; 0x000055d10ce1af4a in inner\_start\_thread ()  
#4&nbsp; 0x00007f4b23ce06ca in start\_thread () at /lib64/libpthread.so.0  
#5&nbsp; 0x00007f4b23803f7f in clone () at /lib64/libc.so.6

Thread 6 (Thread 0x7f4b06eef700 (LWP 5118)):  
#0&nbsp; 0x00007f4b23ce6809 in pthread\_cond\_timedwait@@GLIBC\_2.3.2 () at /lib64/libpthread.so.0  
#1&nbsp; 0x000055d10cd6b9ab in worker\_thread ()  
#2&nbsp; 0x000055d10cd661d6 in start\_wrapper ()  
#3&nbsp; 0x000055d10ce1af4a in inner\_start\_thread ()  
#4&nbsp; 0x00007f4b23ce06ca in start\_thread () at /lib64/libpthread.so.0  
#5&nbsp; 0x00007f4b23803f7f in clone () at /lib64/libc.so.6

Thread 5 (Thread 0x7f4b18044700 (LWP 5117)):  
#0&nbsp; 0x00007f4b23ce6809 in pthread\_cond\_timedwait@@GLIBC\_2.3.2 () at /lib64/libpthread.so.0  
#1&nbsp; 0x000055d10ce19a7d in mono\_thread\_info\_sleep ()  
#2&nbsp; 0x000055d10cd6a91e in monitor\_thread ()  
#3&nbsp; 0x000055d10cd661d6 in start\_wrapper ()  
#4&nbsp; 0x000055d10ce1af4a in inner\_start\_thread ()  
#5&nbsp; 0x00007f4b23ce06ca in start\_thread () at /lib64/libpthread.so.0  
#6&nbsp; 0x00007f4b23803f7f in clone () at /lib64/libc.so.6

Thread 4 (Thread 0x7f4b0c301700 (LWP 5116)):  
#0&nbsp; 0x00007f4b23ce6809 in pthread\_cond\_timedwait@@GLIBC\_2.3.2 () at /lib64/libpthread.so.0  
#1&nbsp; 0x000055d10cdefb3f in \_wapi\_handle\_timedwait\_signal\_handle ()  
#2&nbsp; 0x000055d10ce06304 in wapi\_WaitForSingleObjectEx ()  
#3&nbsp; 0x000055d10cd659d8 in mono\_wait\_uninterrupted.isra.18.constprop ()  
#4&nbsp; 0x000055d10cd65aa3 in ves\_icall\_System\_Threading\_WaitHandle\_WaitOne\_internal ()  
#5&nbsp; 0x00000000420b2604 in&nbsp; ()  
#6&nbsp; 0x0000000000000002 in&nbsp; ()  
#7&nbsp; 0x0000000000000001 in&nbsp; ()  
#8&nbsp; 0x0000000000000064 in&nbsp; ()  
#9&nbsp; 0x00007f4b1c6a7bb0 in&nbsp; ()  
#10 0x0000000000000063 in&nbsp; ()  
#11 0x00007f4b08001960 in&nbsp; ()  
#12 0x00007f4b1c6a7bb0 in&nbsp; ()  
#13 0x00007f4b0c300660 in&nbsp; ()  
#14 0x00007f4b0c3005d0 in&nbsp; ()  
#15 0x00000000420b23d0 in&nbsp; ()  
#16 0x0000000000000000 in&nbsp; ()

Thread 3 (Thread 0x7f4b1cb62700 (LWP 5112)):  
#0&nbsp; 0x00007f4b23ce8957 in do\_futex\_wait.constprop () at /lib64/libpthread.so.0  
#1&nbsp; 0x00007f4b23ce8a04 in \_\_new\_sem\_wait\_slow.constprop.0 () at /lib64/libpthread.so.0  
#2&nbsp; 0x00007f4b23ce8aaa in sem\_wait@@GLIBC\_2.2.5 () at /lib64/libpthread.so.0  
#3&nbsp; 0x000055d10cd877bb in finalizer\_thread ()  
#4&nbsp; 0x000055d10cd661d6 in start\_wrapper ()  
#5&nbsp; 0x000055d10ce1af4a in inner\_start\_thread ()  
#6&nbsp; 0x00007f4b23ce06ca in start\_thread () at /lib64/libpthread.so.0  
#7&nbsp; 0x00007f4b23803f7f in clone () at /lib64/libc.so.6

Thread 2 (Thread 0x7f4b1c3ff700 (LWP 5108)):  
#0&nbsp; 0x00007f4b23ce6460 in pthread\_cond\_wait@@GLIBC\_2.3.2 () at /lib64/libpthread.so.0  
#1&nbsp; 0x000055d10cde963f in thread\_func ()  
#2&nbsp; 0x00007f4b23ce06ca in start\_thread () at /lib64/libpthread.so.0  
#3&nbsp; 0x00007f4b23803f7f in clone () at /lib64/libc.so.6

Thread 1 (Thread 0x7f4b247fa780 (LWP 5104)):  
#0&nbsp; 0x00007f4b23ce9fdb in waitpid () at /lib64/libpthread.so.0  
#1&nbsp; 0x000055d10cc73870 in mono\_handle\_native\_sigsegv ()  
#2&nbsp; 0x000055d10ccca8ce in mono\_arch\_handle\_altstack\_exception ()  
#3&nbsp; 0x000055d10cbec633 in mono\_sigsegv\_signal\_handler ()  
#4&nbsp; 0x00007f4b23cea5c0 in \<signal handler called\> () at /lib64/libpthread.so.0  
#5&nbsp; 0x00007f4b23788fe6 in strlen () at /lib64/libc.so.6  
#6&nbsp; 0x00007f4b1870b8fe in \_XimLocalUtf8LookupString () at /lib64/libX11.so.6  
#7&nbsp; 0x000000004249f8b5 in&nbsp; ()  
#8&nbsp; 0x0000000000000000 in&nbsp; ()

=================================================================  
Got a SIGSEGV while executing native code. This usually indicates  
a fatal error in the mono runtime or one of the native libraries  
used by your application.  
=================================================================[/code]

Ок. Теперь уже проще гуглить. Ответ был найден на форуме arch-linux в [ветке](https://bbs.archlinux.org/viewtopic.php?id=213818), которая посвящена keepass: это оказался баг в библиотеке WinForms, который не сильно важен для разработчиков и никто фиксить его пока не будет ([оригинальный репорт](https://bugzilla.xamarin.com/show_bug.cgi?id=41505)).

Временное решение - добавить опцию --verify-all в качестве аргумента mono.

[code]env LANG=en\_US.utf8 mono --verify-all RepetierHost.exe -home {$HOME}.soft/RepetierHost[/code]

С этой опцией вылетов не наблюдается, но случаются фризы приложения.

