angular.module('app').factory('tree', ['builder', function(builder) {
    var model = function Model(data) {
        this.$unwrap(data)
    };

    model.prototype.$unwrap = function(data) {
        angular.extend(this, builder.buildSingleFields(data));
    };

    return model;
}]);