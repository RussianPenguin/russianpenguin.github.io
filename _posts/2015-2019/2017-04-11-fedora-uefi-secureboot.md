---
layout: post
title: Fedora перестает грузиться на UEFI после обновения (и показывает MOK)
type: post
categories: []
tags:
- fedora
- linux
- uefi
permalink: "/2017/04/11/fedora-uefi-secureboot/"
excerpt: В статье рассказывается о проблемах загрузки fedora на uefi+secureboot после
  обновления
---
Никогда бы не подумал, но вчера столкнулся с проблемой при которой после обновления fedora начисто отказалась загружаться постоянно выдавая при старте окно MokManager с просьбой добавить ключи или хеши с secureboot.

Что меня больше всего удивило так это то, что efibootmgr -v выдавал кучу записей загрузчиков shim.efi с некорректными uuid разделов на которых они размещены.

```
$ efibootmgr -v  
BootCurrent: 0002  
Timeout: 0 seconds  
BootOrder: 0007,0002,2001,2002,2003  
Boot0000* Fedora    HD(1,GPT,f627bf87-5440-4997-8310-aa80dba7e383,0x800,0x64000)/File(\EFI\Fedora\shim.efi)  
Boot0001* Fedora    HD(1,GPT,f627bf87-5440-4997-8310-aa80dba7e383,0x800,0x64000)/File(\EFI\fedora\shim.efi)  
Boot0002* Linux    PciRoot(0x0)/Pci(0x1c,0x4)/Pci(0x0,0x0)/NVMe(0x1,00-00-00-00-00-00-00-00)/HD(1,GPT,f627bf87-5440-4997-8310-aa80dba7e383,0x800,0x64000)/File(\EFI\BOOT\BOOTX64.EFI)A01 ..  
Boot0003* Fedora    HD(1,GPT,f627bf87-5440-4997-8310-aa80dba7e383,0x800,0x64000)/File(\EFI\fedora\shim.efi)  
Boot0004* Fedora    HD(1,GPT,f627bf87-5440-4997-8310-aa80dba7e383,0x800,0x64000)/File(\EFI\fedora\shim.efi)  
Boot0005* Fedora    HD(1,GPT,f627bf87-5440-4997-8310-aa80dba7e383,0x800,0x64000)/File(\EFI\fedora\shim.efi)  
Boot0006* Fedora    HD(1,GPT,f627bf87-5440-4997-8310-aa80dba7e383,0x800,0x64000)/File(\EFI\fedora\shim.efi)  
Boot0007* Fedora    HD(1,GPT,f627bf87-5440-4997-8310-aa80dba7e383,0x800,0x64000)/File(\EFI\fedora\shim.efi)  
Boot2001* EFI USB Device    RC  
Boot2002* EFI DVD/CDROM    RC  
Boot2003* EFI Network    RC  

```

Конечно в данном листинге уже все верно поскольку он был сделан на рабочей машине, но в оригинальном листинге в идентификаторе HD были прописаны несуществующие uuid разделов. И подобных записей было далеко за 20 штук.

К сожалению мне неизвестна причина по которой система прописывает неверные данные, но мне нужно было оживить машину. Для этого следует сначала [зайти](https://docs.fedoraproject.org/en-US/Fedora/22/html/Multiboot_Guide/common_operations_appendix.html#common-chroot_from_live) в chroot окружение убитой системы.

Далее нам потребуется удалить все записи загрузчика с неверными данными. Это записи вида Boot0ХХХ.

Сначала надо переустановить grub-efi и shim как это рекомендует документация.

```
# dnf reinstall grub-efi shim
```

Теперь удаляем невалидные записи. Для их удаления нам потребуется выполнять команду

```
# efibootmgr -B -b XXXX
```

- **-B** - удалить запись
- **-b XXXX** - выбрать активной запись XXXX

В качестве XXXX будут выступать идентификаторы неугодных записей (не трогайте записи, которые начинаются не с нуля - они системные). И конечно же перед каждым удалением следите за состоянием записей (efibootmgr -v).

Последним шагом будет добавление правильной записи.

```
efibootmgr -c -w -L Fedora -d /dev/nvme0n1 -p 1 -l '\EFI\Fedora\shim.efi'
```

- **-c** - создать запись
- **-w** - сделать запись в mbr если это требуется
- **-L Fedora** - метка новой записи в загрузчике
- **-d /dev/nvme0n1** - жесткий диск на котором размещен efi-раздел (у вас может быть /dev/sda или любой другой)
- **-p 1** - номер раздела на диске (если efi у вас это /dev/sda1, то 1, sda2 - 2 и т.д.)
- **-l '\EFI\Fedora\shim.efi'** - расположение файла загрузчика относительно корня диска efi (а не корня файловой системы в которую он подмонтирован). Обратите внимание, что тут нам обязательно надо указать загрузчик shim.efi, а не что-то другое.

После завершения можно перезагружаться и пробовать войти в систему. Mok Manager больше не должен появляться. Если это не так, то где-то вы допустили ошибку.

## Литература

- [Как попасть в chroot с livecd](https://docs.fedoraproject.org/en-US/Fedora/22/html/Multiboot_Guide/common_operations_appendix.html#common-chroot_from_live)
- [Восстановление загрузчика grub-efi](https://docs.fedoraproject.org/en-US/Fedora/22/html/Multiboot_Guide/GRUB-reinstalling.html)
- [Update fedora 25 breaks UEFI - MOK - drive cant boot (система перестает грузиться и постоянно показывает MokManager)](https://bugzilla.redhat.com/show_bug.cgi?id=1413191)
