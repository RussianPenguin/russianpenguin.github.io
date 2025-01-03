---
layout: post
title: Tmux - лучшая альтернатива screen
type: post
categories: []
tags:
- linux
permalink: "/2015/04/02/tmux-%d0%bb%d1%83%d1%87%d1%88%d0%b0%d1%8f-%d0%b0%d0%bb%d1%8c%d1%82%d0%b5%d1%80%d0%bd%d0%b0%d1%82%d0%b8%d0%b2%d0%b0-screen/"
---
Довольно долго пытался пересесть с GNU Screen на tmux, но вот только сдерживало несколько вещей в нем.  
На [сайте](http://tmux.sourceforge.net/ "tmux is a terminal multiplexer") проекта можно найти описание всех фич, я же опишу лишь те, которые для меня оказались самыми полезными.

- **Улучшенная модель отрисовки** (да-да screen периодически подключивал)
- **Содержимое терминала не теряется, когда мып ользуемся редакторами** (открыли vim, закрыли, история осталась а месте и можно прокрутить до нее)
- **Конфигурирование на лету**

### C-b

Ага. Это сочетание клавишь из vim.

Переключим tmux на работу с C-a

```
set-option -g prefix C-a
```

### Задержка между контрольными клавишами C-b и непосредственным вводом команды (например n).

Решается просто

```
set -s escape-time 0
```

### Сочетание клавиш для управления tmux внутри tmux

Бывает такое, что приходится внутри tmux запустить вложенную сессию tmux. Значит надо как-то пробросить этой вложенной сессии команды. Например **C-a a _command_**

```
bind-key a send-prefix
```

### Начинаме считать окна с 1, а не 0

Это удобно, так как 0 находится где-то на отшибе. И к нему надо тянуться.

```
set -g base-index 1
```

### Агрессивный ресайз терминалов

Дефолтно при подключении к сессии нескольких клиентов рамер окна выбирается по размеру самого маленького клиента. Это неинтересно. Поэтому позволим терминалу для каждого клиента иметь собственный размер.

```
setw -g aggressive-resize on
```

### После завершения команды можно ввести другую команду не нажимая C-b

Для меня эта проблема была самой неприятно. Воспроизвести ее просто: нужно на готом tmux создать два региона.

```
C-b "
```

А затем переключаться между ними.

```
C-b Up Down Up Down
```

Вы заметите, что нажав один раз C-b, а потом быстро нажимая вверх-вниз окна переключаются.

Это неприятно когда пытаешься переключить вкладку и начать тут же вводить что-то.

За такое поведение отвечает repeat-time

```
repeat-time time  
 Allow multiple commands to be entered without pressing  
 the prefix-key again in the specified time milliseconds  
 (the default is 500). Whether a key repeats may be set  
 when it is bound using the -r flag to bind-key. Repeat  
 is enabled for the default keys bound to the resize-pane  
 command.
```

Можно отключить repeat для команд переключения между панелями и окнами просто переобъявив команду без флага -r. Например так:

```
bind-key Up select-pane -U
```

Но я же предпочитаю эту фичу отключить вовсе.

```
set-option -g repeat-time 0
```

### Базовый ~/.tmux.conf

```
# C-b не подходит - так как используется в vim  
set-option -g prefix C-a  
bind-key C-a last-window

# Начинам нумерацию окон с 1  
set -g base-index 1

# Добавляем отзывчивости в реакции на команды  
set -s escape-time 0

# Отключаем ожидание дополнительных команд  
set-option -g repeat-time 0

# Или можно забиндить команды отдельно отключая repeat только для нужных  
#bind-key Up select-pane -U

# Цветовая схема  
set -g status-bg black  
set -g status-fg white  
set -g status-left ""  
set -g status-right "#[fg=green]#H"

# Ресайзим содержимое окна индивидуально для каждого клиента  
setw -g aggressive-resize on

# Позволим пробрасывать команды внутрь вложенной сессии tmux  
# при помощи C-a a <command>  
bind-key a send-prefix

# Мониторинг активности  
#setw -g monitor-activity on  
#set -g visual-activity on

# Пример использования команды оболочки в статусной строке  
#set -g status-right "#[fg=yellow]#(uptime | cut -d ',' -f 2-)"

# Подсвечиваем активное окно  
set-window-option -g window-status-current-bg green
```

За кадром осталось использование [tmuxinator](https://github.com/tmuxinator/tmuxinator "Tmuxinator") для запуска tmux c определенной заранее раскладкой окон.

Ну и еще про настройку можно почитать в [Arch-wiki](https://wiki.archlinux.org/index.php/Tmux "ArchWiki: Tmux").

