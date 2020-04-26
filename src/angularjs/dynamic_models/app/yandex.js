angular.module('ymaps', []);

angular.module('ymaps').service('ymaps', ['$q', function($q) {

    var $ymapsReady = $q.defer();

    ymaps.ready(function() {
        $ymapsReady.resolve();
    });

    this.execute = function execute(callback) {
        var defer = $q.defer();

        $ymapsReady.promise.then(function() {
            defer.resolve(callback(ymaps));
        });

        return defer.promise
    };
}]);

angular.module('ymaps').service('ymapsStore', function() {
    this.maps = {}
});

angular.module('ymaps').directive('ymap', ['ymaps', 'ymapsStore', function(ymaps, ymapsStore) {
    return {
        restrict: 'A',
        link: function ($scope, $element, $attrs) {
            $id = $attrs['ymap'];
            $element.prop('id', $id);

            ymaps.execute(function(ymaps) {
                var map = new ymaps.Map("YMapsID", {
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