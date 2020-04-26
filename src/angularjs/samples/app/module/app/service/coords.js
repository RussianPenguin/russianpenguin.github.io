angular.module('app').factory('coords', ['$timeout', 'ymaps', '$q', '$log', function($timeout, $ymaps, $q, $log) {
    var coords = function Coords($futureCoordsData) {
        this.$dirty = true;

        if ($futureCoordsData.$$state) {
            this.$unwrap($futureCoordsData)
        } {
            angular.extend(this, $futureCoordsData);
            this.$dirty = false;
        }
    };

    /**
     * Инициализирует объект в момент фактического получения данных.
     * @param $futureCityData
     */
    coords.prototype.$unwrap = function($futureCoordsData) {
        var self = this;
        this.$futureCoordsData = $futureCoordsData;
        $futureCoordsData.then(function(data) {
            $timeout(function() {
                angular.extend(self, data);
                self.$dirty = false;
            })
        })
    };

    coords.$find = function ($cityName) {
        var promise = $q.defer();

        $ymaps.execute(function(ymaps) {
            ymaps.geocode($cityName).then(function(res) {
                var lonlat = res.geoObjects.get(0).geometry.getCoordinates();
                promise.resolve({lon: lonlat[0], lat: lonlat[1]})
            });
        });

        return new coords(promise.promise)
    };

    return coords;
}])