angular.module('app').directive('selectItem', ['$log', function($log) {
    return {
        require: '^ngModel',
        restrict: 'E',
        scope: {
            list: '=',
            model: '='
        },
        templateUrl: '/tmpl/selectitem.html',
        link: function($scope, $element, $attrs, $ngModelCtrl) {
            $log.log('link');
            $scope.click = function(event, item) {
                $log.log('click')
                $(event.target).siblings().removeClass('selected')
                $(event.target).addClass('selected')
                $ngModelCtrl.$setViewValue(item, event && event.type);
            }
            
        },
        controller: function($scope, $element, $attrs) {
            $log.log('controller');
        }
    }
}])