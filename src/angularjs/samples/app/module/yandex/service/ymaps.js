angular.module('yandex').service('ymaps', ['$q', function($q) {
    var $promise = $q.defer()
    
    ymaps.ready(function() {
        $promise.resolve();
    })
    
    this.execute = function(callback) {
        $q.when($promise.promise, function() {
            callback(ymaps)
        })
    }
}])