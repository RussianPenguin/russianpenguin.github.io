angular.module('yandex').directive('ymap', ['ymaps', 'ymapsStore', function(ymaps, ymapsStore) {
    return {
        restrict: 'E',
        link: function ($scope, $element, $attrs) {
            $id = $attrs['id'];

            ymaps.execute(function(ymaps) {
                var map = new ymaps.Map($id, {
                    center: [55.76, 37.64],
                    zoom: 10
                });

                map.controls.add(
                    new ymaps.control.ZoomControl()
                );

                ymapsStore.maps[$id] = map
            });

            $scope.$on('$destroy', function() {
                delete ymapsStore.maps[$id]
            })
        }
    }
}]);