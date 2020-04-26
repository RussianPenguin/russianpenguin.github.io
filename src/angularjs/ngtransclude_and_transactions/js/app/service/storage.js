angular.module('app').service('storage',
    ['$location', '$rootScope', '$log', function($location, $rootScope, $log) {

    var self = this;

    /**
     * Параметры, с которыми может работать модель
     * @type {string[]}
     */
    this.options = ['a', 'b', 'c'];

    /**
     * Уровень вложенности транзакции.
     * @type {number}
     */
    var transactionNestedLevel = 0;

    /**
     * Данные, с которыми система будет работать в рамках транзакции
     * @type {null}
     */
    var transactionData = null;

    /**
     * Стартуем транзакцию
     */
    this.begin = function() {
        $log.log('begin transaction at level ' + transactionNestedLevel);
        if (transactionNestedLevel == 0) {
            transactionData = angular.extend({}, $location.search());
        }
        transactionNestedLevel++;
    };

    /**
     * Коммит Означает, что все наши изменения таки будут применены
     */
    this.commit = function() {
        $log.log('commit transaction at level ' + transactionNestedLevel);
        transactionNestedLevel--;
        if (transactionNestedLevel == 0) {
            $location.search(transactionData);
        }
    };

    /**
     * Откатываем транзакцию - никаких изменений не будет применено
     */
    this.rollback = function() {
        $log.log('rollback transaction at level ' + transactionNestedLevel);
        transactionNestedLevel--;
        if (transactionNestedLevel == 0) {
            transactionData = $location.search()
        }
    };

    /**
     * Установка свойства (в данном примере мы работаем с $location)
     * Учитывайте, что работая с рамках транзакции мы должны использовать transactionData
     * @param key
     * @returns {*}
     */
    this.get = function(key) {
        //$log.log('get value at key: ' + key + ' (transaction is '+ ((transactionNestedLevel == 0) ? 'inactive' : 'active') + ')');
        if (transactionNestedLevel == 0) {
            return $location.search()[key]
        } else {
            return transactionData[key];
        }
    };

    /**
     * Получаем информацию из $location по ключу
     * Учитывайте, что работая с рамках транзакции мы должны использовать transactionData
     * @param key
     * @param value
     */
    this.set = function(key, value) {
        //$log.log('set value "' + key + '" to ' + value + ' (transaction is '+ ((transactionNestedLevel == 0) ? 'inactive' : 'active') + ')');
        if (transactionNestedLevel == 0) {
            var data = $location.search();
            data[key] = value;
            $location.search(data);
        } else {
            transactionData[key] = value;
        }

        return value;
    };

    /**
     * Создание сеттера и геттера для параметра name
     * @param name
     */
    this.bindGetterSetter = function(name) {
        var self = this;
        this[name] = function(value) {
            return arguments.length ? self.set(name, value) : self.get(name);
        }
    };

    /**
     * Генерируем сеттеры и геттеры для всех доступных параметров модели
     */
    for (var idx in this.options) {
        this.bindGetterSetter(this.options[idx]);
    }
}]);