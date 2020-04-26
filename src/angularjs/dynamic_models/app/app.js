// Array Remove - By John Resig (MIT Licensed)
Array.prototype.remove = function(from, to) {
    var rest = this.slice((to || from) + 1 || this.length);
    this.length = from < 0 ? this.length + from : from;
    return this.push.apply(this, rest);
};

angular.module('app', ['ymaps']);

/**
 * Автодополнение по городам.
 */
angular.module('app').service('autocomplete', ['$timeout', 'citySuggest', function($timeout, citySuggest) {

    var self = this;

    this.update = function($text) {
        citySuggest.get($text).then(function(data) {
            $timeout(function() {
                self.autocomplete = data.data
            });
        })
    };

    this.autocomplete = []
}]);

angular.module('app').service('citySuggest', ['$http', function($http) {
    this.get = function ($text) {
        return $http.get('/api.php/complete/' + $text);
    }
}]);

angular.module('app').service('bucket', function() {
    var items = [];

    this.add = function add(item) {
        items.push(item);
    };

    this.remove = function remove(item) {
        items.remove(items.indexOf(item));
    };

    this.list = function list() {
        return items;
    }
});
angular.module('app').service('cityApi', ['$http', function($http) {
    this.get = function ($id) {
        return $http.get('/api.php/city/' + $id);
    }
}]);

angular.module('app').service('city', ['$timeout', 'cityApi', 'coords', 'ymapsStore', function($timeout, cityApi, coords, ymapsStore) {
    var city = function City($futureCityData) {

        /**
         * Признак того, что объект еще не проинициализовано до конца
         */
        this.$dirty = true;

        // Если мы получили объект типа $q, то должны дождаться, когда он будет зарезолвен
        if ($futureCityData.$$state) {
            this.$unwrap($futureCityData)
        } else {
            _.extend(this, $futureCityData);
            this.$dirty = false;
            this.coords = coords.$find(this.name);
        }
    };

    /**
     * Инициализирует объект в момент фактического получения данных.
     * @param $futureCityData
     */
    city.prototype.$unwrap = function($futureCityData) {
        var self = this;
        this.$futureCityData = $futureCityData;
        $futureCityData.then(function(data) {
            $timeout(function() {
                _.extend(self, data.data);
                self.$dirty = false;
                console.log('get coordinates for ' + self.name);
                self.coords = coords.$find(self.name);
            })
        })
    };

    city.prototype.goto = function($mapId) {
        console.log([this.coords.lon, this.coords.lat]);
        ymapsStore.maps[$mapId].setCenter([this.coords.lon, this.coords.lat]);
    };

    city.prototype.$destroy = function() {

    };

    city.$find = function $find($id) {
        return new city(cityApi.get($id));
    };

    return city;
}]);

angular.module('app').controller('app', ['$scope', 'autocomplete', 'city', 'bucket', function($scope, autocomplete, city, bucket) {
    $scope.choice = null;
    $scope.selected = null;
    $scope.autocomplete = autocomplete;
    $scope.bucket = bucket;

    $scope.$watch('choice', function(newVal) {
        autocomplete.update(newVal);
    });



    $scope.add = function($id) {
        bucket.add(city.$find($id));
    }

}]);