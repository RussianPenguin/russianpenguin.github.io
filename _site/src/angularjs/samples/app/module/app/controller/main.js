angular.module('app').controller("main", ['$scope', '$timeout', '$log', 'informer', function($scope, $timeout, $log, $informer) {  
    $scope.list = [1, 2, 3]
    $scope.model = '';
    $scope.informer = $informer;
}]);