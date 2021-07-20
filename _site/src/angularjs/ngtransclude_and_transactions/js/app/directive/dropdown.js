angular.module('app').directive('dropdown', ['$log', function($log) {
    return {
        restrict: 'E',
        transclude: true,
        scope: {
            options: '='
        },
        templateUrl: './tmpl/dropdown.html',
        link: function($scope, $element, $attrs) {

            /**
             * Открываем список опций при клике
             */
            $element.find('.open').on('click', function() {

                if ($element.find('.options').hasClass('ng-hide')) {
                    $scope.options.begin();
                    $element.find('.options').removeClass('ng-hide');
                } else {
                    $scope.options.rollback();
                    $element.find('.options').addClass('ng-hide');
                }
                $scope.$applyAsync();
            });

            /**
             * Отменяем транзакцию
             */
            $element.find('.discard').on('click', function() {
                $scope.options.rollback();
                $element.find('.options').addClass('ng-hide');
                $scope.$applyAsync();
            });

            /**
             * Применяем транзакцию
             */
            $element.find('.apply').on('click', function() {
                $scope.options.commit();
                $element.find('.options').addClass('ng-hide');
                $scope.$applyAsync();
            })
        }
    }
}]);