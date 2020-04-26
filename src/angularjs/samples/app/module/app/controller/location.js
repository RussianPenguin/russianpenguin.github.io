angular.module('app').controller('location', ['$scope', '$location', function($scope, $location) {
    var self = this;
    this.options = ["a", "b", "c"];
    
    $scope.list = {
        iterate: function() {
            return self.options;
        }
    }
    
    this.get = function(key) {
        return $location.search()[key]
    };

    this.set = function(key, value) {
        var data = $location.search();
        data[key] = value;
        $location.search(data);
        return value;
    };
    
    /**
     * Создание сеттера и геттера для параметра name
     * @param name
     */
    this.bindGetterSetter = function(name) {
        var self = this;
        $scope.list[name] = function(value) {
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