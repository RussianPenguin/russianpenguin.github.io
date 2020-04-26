angular.module('app').directive('checkbox', [function() {
    return {
        restrict: "E",
        templateUrl: './tmpl/checkbox.html',
        scope: {
            options: '='
        }
    }
}]);