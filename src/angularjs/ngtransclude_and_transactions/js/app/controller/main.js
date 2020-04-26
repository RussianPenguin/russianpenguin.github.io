angular.module('app').controller('main', ['$scope', 'storage', function($scope, storage) {
    $scope.storage = storage;
}]);