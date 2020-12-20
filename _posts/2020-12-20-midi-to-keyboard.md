layout: post
title: 'MIDI: Превращаем сообщения в нажатия кнопок клавиатуры'
type: post
categories:
- HowTo
tags:
- jackd
- linux
- midi
permalink: "/2020/12/20/midi-to-keyboard-event/

Как конвертировать сообщения от midi-клавиатуры в нажатия кнопок на клавиатуре?

Где это может потребоваться - это в приложениях, которые не умеют в миди, но умеют в хоткеи. Например musescore - это приложение очень плохо умеет в миди.

Создадим простейший скрипт midi-to-keyboard. Все что он делает - это слушает события на шине и транслирует их в нажатия кнопок клавиатуры.

```bash
#!/usr/bin/env bash
THROTTLE=150
old_time=0
last_change=0
#aseqdump -p "Arturia MiniLab mkII" | \
aseqdump -p "System" | \
while IFS=" ," read src ev1 ev2 ch label1 data1 label2 data2 rest; do
    current_time=$(($(date +%s%N)/1000000))
    diff=$(($current_time-$old_time))
    if [[ $diff -gt $THROTTLE && "$ch" = 15 ]]; then
        # тротлинг событий обязателен
        change=0
        case $data1 in
            48 ) # effects 1
                if [[ $data2 -gt 64 ]]; then
                    xdotool key Right
                    change=1
                elif [[ $data2 -lt 64 ]]; then
                    xdotool key Left
                    change=1
                fi
            ;;
            49 ) # effects 2
                if [[ $data2 -gt 64 ]]; then
                    xdotool key Up
                    change=1
                elif [[ $data2 -lt 64 ]]; then
                    xdotool key Down
                    change=1
                fi
            ;;
        esac
        if [[ change -eq 1 ]]; then
            echo "Change after $(($current_time-$old_time))"
            old_time=$current_time
        fi
    fi
done
```

**Литература:**
- [SO: Translating MIDI input into computer keystrokes on Linux?](https://superuser.com/questions/1170136/translating-midi-input-into-computer-keystrokes-on-linux)

