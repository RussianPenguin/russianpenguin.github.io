# Чтобы не забыть

## Настройка

**Установка rbenv**

Альтернатива rvm для тех, кто разработчиком на ruby не является.

Обязательно [проверяем](https://github.com/rbenv/rbenv/wiki) наличие shims в `PATH`. А потом ставим нужную версию. Блог работает на ruby 2.7

```shell
$ rbenv install 3.1.2
# в каталоге проекта указываем, что работать надо с конкретной версией
$ rbenv local 3.1.2
```

**Установка зависимостей**

Предварительно [убеждаемся](https://guilhermesimoes.github.io/blog/installing-gems-per-project-directory), что bundle настроен на установку gem'ов в `vendor/bundle`.

```shell
$ bundle config path
Set for your local app (./.bundle/config): "vendor/bundle"
```

Если нет, то указываем настройку.

```shell
$ bundle config path 'vendor/bundle' --local
```
