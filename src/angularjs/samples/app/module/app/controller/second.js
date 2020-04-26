angular.module('app').controller('second', ['$scope', 'informer', function($scope, $informer) {
    $scope.informer = $informer;
}])