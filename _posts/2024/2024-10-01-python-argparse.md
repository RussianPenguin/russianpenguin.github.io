---
layout: post
title: 'Python: парсим аргументы командной строки'
type: post
status: publish
categories:
- HowTo
tags:
- python
- console
permalink: "/2024/10/01/python-argparse"
---

Сегодня разберемся с сабпарсерами объекта `argparse.ArgumentParser`, которые передаются через параметр `parents`.

В общем случае при попытке распарсить аргументы командной строки мы создаем объект и начинаем методично добавлять к нему все известные нам параметры.

Иногда можно встретить очень большие листинги. Но существует механизм, который позволяет разбивать парсеры на более мелкие составляющие и сцеплять их между собой для более простого понимания (кстати, очень полезно при создании системы плагинов).

При этом мы можем управлять тем, какие аргументы будут парсится.

Для этого в параметры `parents` можно передать те объекты, от которых надо унаследоваться.

Рассмотрим на примерах.

В первом случае создается `ArgumentParser` с одним аргументом. И он сразу же разбирает командную строку на известные параметры и хвост при помощи `parse_known_args`.

В зависимости от значения `-l` мы либо продолжим работу, либо остановимся.

```python
import argparse  
  
parser = argparse.ArgumentParser(add_help=False)  
parser.add_argument('-l', '--list', help="List something", action='store_true')  
args, remaining = parser.parse_known_args()  
  
if args.list:  
   print('List')  
   exit(0)  
  
parser = argparse.ArgumentParser(  
   description="Chained parsers",  
   formatter_class=argparse.RawDescriptionHelpFormatter,  
   parents=[parser])  
  
parser.add_argument('-t', '--test', help="Test", action='store_true')  
other_args = parser.parse_args(remaining)  
  
print('Args: {}'.format(other_args))
```

Второй пример чуть более сложный.

Допустим, что код обрабатывает видеофайлы и аудиофайлы. В зависимости от этого у него меняется поведение атрибута `-r`.

```python
import argparse  
import enum  
  
class Formats(enum.Enum):  
   VIDEO='video'  
   AUDIO='audio'  
  
   def __str__(self):  
       return self.value  
  
def parser_common():  
   parser = argparse.ArgumentParser(add_help=False)  
   parser.add_argument(  
       '-f', '--format', help="Specify format", type=Formats, choices=list(Formats)  
   )  
   return parser  
  
def parser_video(parent_parser: argparse.ArgumentParser) -> argparse.ArgumentParser:  
   parser = argparse.ArgumentParser(  
       description="Video parser",  
       formatter_class=argparse.RawDescriptionHelpFormatter,  
       parents=[parent_parser])  
   parser.add_argument(  
       '-r',  
       '--resolution',  
       help="Resolution",  
       type=int,  
       nargs=2,  
       metavar=('WIDTH', 'HEIGHT'),  
       required=True,  
   )  
   return parser  
  
def parser_audio(parent_parser: argparse.ArgumentParser) -> argparse.ArgumentParser:  
   parser = argparse.ArgumentParser(  
       description="Audio parser",  
       formatter_class=argparse.RawDescriptionHelpFormatter,  
       parents=[parent_parser])  
   parser.add_argument(  
       '-r',  
       '--resolution',  
       help="Audit resolution",  
       type=int,  
       required=True,  
   )  
   return parser  
  
def main():  
   parser = parser_common()  
   args, remaining = parser.parse_known_args()  
   if args.format == Formats.AUDIO:  
       parser = parser_audio(parser)  
   elif args.format == Formats.VIDEO:  
       parser = parser_video(parser)  
   else:  
       parser.print_help()  
       exit(1)  
  
   other_args = parser.parse_args(remaining)  
   print(2)  
   print('Args: {}'.format(other_args))  
  
if __name__ == '__main__':  
   main()
```

Запустите и посмотрите, как ведёт себя данный код.

```shell
$ python argsparser.py
usage: argsparser.py [-f {video,audio}]

options:
  -f {video,audio}, --format {video,audio}
                        Specify format
$ python argsparser.py -f audio
usage: argsparser.py [-h] [-f {video,audio}] -r RESOLUTION
argsparser.py: error: the following arguments are required: -r/--resolution
$ python argsparser.py -f video
usage: argsparser.py [-h] [-f {video,audio}] -r WIDTH HEIGHT
argsparser.py: error: the following arguments are required: -r/--resolution
$ python argsparser.py -f video -h
usage: argsparser.py [-h] [-f {video,audio}] -r WIDTH HEIGHT

Video parser

options:
  -h, --help            show this help message and exit
  -f {video,audio}, --format {video,audio}
                        Specify format
  -r WIDTH HEIGHT, --resolution WIDTH HEIGHT
                        Resolution
$ python argsparser.py -f audio -h
usage: argsparser.py [-h] [-f {video,audio}] -r RESOLUTION

Audio parser

options:
  -h, --help            show this help message and exit
  -f {video,audio}, --format {video,audio}
                        Specify format
  -r RESOLUTION, --resolution RESOLUTION
                        Audit resolution

```