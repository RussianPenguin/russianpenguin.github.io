angular.module('app').controller('yandex', ['$scope', 'coords', 'ymapsStore', function($scope, coords, ymapsStore) {
    $scope.cityName = ''
    $scope.cities = []
    $scope.locate = function() {
        if ($scope.cityName) {
            $scope.cities.push({
                'name': $scope.cityName,
                'coords': coords.$find($scope.cityName)
            })
        }
    }
    
    $scope.show = function(mapId, city) {
        ymapsStore.maps[mapId].setCenter([city.coords.lon, city.coords.lat]);
    }
}])