"use strict";

angular.module("app").config(['$routeProvider', function($routeProvider) {

    $routeProvider
        .when('/play', {
            templateUrl: 'templates/player.html',
            controller: 'PlayerController'
        })
        .otherwise({
            redirectTo: '/play'
        });
}]);