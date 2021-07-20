angular.module('app').controller('binding', ['$scope', function($scope) {
    $scope.twoway = 'twoway'
    $scope.oneway = 'oneway'
    $scope.showhide = false
    
    $scope.invert = function() {
        $scope.showhide = !$scope.showhide
    }
}])