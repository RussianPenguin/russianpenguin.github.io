angular.module('app').factory('place', ['builder', function(builder) {
    var model = function Model(data) {
        this.$unwrap(data)
    };

    model.prototype.$unwrap = function(data) {
        angular.extend(this, builder.buildSingleFields(data));
    };

    return model;
}]);