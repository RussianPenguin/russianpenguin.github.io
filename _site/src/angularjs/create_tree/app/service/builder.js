angular.module('app').provider('builder', [function() {

    /**
     * Конфиг будет использоваться в дальнейшем при создании билдера.
     * Конфигурируется в секции .config приложения
     * @type {{}}
     */
    var config = {};

    /**
     * Конструктор принимает на вход словарик моделей.
     * Это есть соответствие названия типа (поле type) и модели в самом ангуляре
     *
     * @param config
     */
    var builder = function Builder(config, $injector) {
        this.config = config;
        this.$injector = $injector;
    };

    /**
     * Расширяет модель model за счет заполнения ее полей данными из data.
     * Если поле data так же является моделью, то создает ее на основе типа (поле type) и словаря моделей,
     * который задается при конфигурировании приложения.
     *
     * @param model
     * @param data
     */
    builder.prototype.buildSingleFields = function(data) {
        var fields = {};
        var self = this;

        angular.forEach(data, function(value, name) {
            fields[name] = self.build(value);
        });
        // заполним модель на базе полей из fields
        return fields;
    };

    /**
     * Точка входа: рекурсивный обход предоставленных данных и формирование дерева моделей
     * @param data
     */
    builder.prototype.build = function(data) {
        // пройдемся по содержимому data
        // очень важный момент: data может быть либо массивом, либо объектом.
        // в обоих случаях подход должен быть разный:
        // в случае массива мы применяем .map, а в случае объекта мы его обрабатываем создавая клон,
        // в котором каждое поле превращается либо в модель, либо копируется без изменений
        // Но и это еще не все: data может быть моделью - это тоже надо предусмотреть.

        if( Object.prototype.toString.call(data) === '[object Array]' ) {
            var fields = [];

            // при обработке массива важно сохранить порядок полей
            for (var idx = 0; idx < data.length; idx++) {
                // создаем элемент с индексом idx
                fields.push(this.build(data[idx]));
            }

            return fields;
        } else if (typeof data === 'object' && data.type !== undefined) { // это у нас модель
            /*
             * Здесь мы пробуем по описанию типа получить модель.
             * Если это удается, то на базе этой модели строится поле/
             * Если же нет (т.е. у нас нет на клиенте описания соответсвующих моделей),
             * то все зависит от решения разработчика.
             *
             * Так же этот участок кода не слишком оптимален - стоит избавится от повторных вызовов .get для одной и той же модели.
             */
            var model = this.$injector.get(this.config[data.type]);
            if (model) {
                return new model(data);
            } else {
                // если не нашли модель, то на усмотрение разработчика
                // один из вариантов - использовать фейковую модель-пустышку
                return null;
            }
        } else if (typeof data === 'object') { // это простой объект
            // создадим структуру, которая будет служить расширением для модели
            // если у нас входящий объект не является простым типом или моделью
            var fields = {};

            var self = this;

            angular.forEach(data, function(value, name) {
                fields[name] = self.build(value);
            });

            return fields;
        } else {
            // простой тип
            return data
        }

    };

    /**
     * Добавляем соответствие типа type и модели modelName.
     * Поскольку в момент конфигурировния фабрики еще недоступны, то используем строковое имя.
     * Одним из вариантом может быть использование в .configure $inject для получения объекта-модели сразу.
     *
     * @param type
     * @param modelName
     */
    this.addModelMap = function(type, modelName) {
        config[type] = modelName;
    };

    this.$get = ['$injector', function($injector) {
        return new builder(config, $injector);
    }]
}]);