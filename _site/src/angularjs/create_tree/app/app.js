angular.module('app', []).config(['builderProvider', function(builderProvider) {
    // инициализируем три модели: пищя, дерево, участок
    builderProvider.addModelMap('food', 'food');
    builderProvider.addModelMap('place', 'place');
    builderProvider.addModelMap('tree', 'tree');
}]);