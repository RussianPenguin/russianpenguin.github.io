angular.module('app').service('coords', ['$timeout', 'ymaps', '$q', function($timeout, $ymaps, $q) {
    var coords = function Coords($futureCoordsData) {
        this.$dirty = true;

        if ($futureCoordsData.$$state) {
            this.$unwrap($futureCoordsData)
        } {
            _.extend(this, $futureCoordsData);
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
                _.extend(self, data);
                self.$dirty = false;
            })
        })
    };

    coords.$find = function ($cityName) {
        var outerdefer = $q.defer();

        var innerPromise = $ymaps.execute(function(ymaps) {
            ymaps.geocode($cityName).then(function(res) {
                var lonlat = res.geoObjects.get(0).geometry.getCoordinates();
                outerdefer.resolve({lon: lonlat[0], lat: lonlat[1]})
            });
        });

        return new coords(outerdefer.promise)
    };

    return coords;
}]);