angular.module('app').controller('http', ['$scope', '$http', '$log', '$q', function($scope, $http, $log, $q) {
    $scope.run = function() {
        /*$http.get('/lecture/1').then(function(data) {
            $log.log(data)
        }, function() {
            $log.log("promise rejected")
        })*/
        
        $http.post('/lecture/9', 
                   {title: "lecture 9", description: "lecture 9"}
                  ).then(function() {
            $log.log("Данные сохранены")
        })
        $scope.defer = $q.defer();
/*        $http.get('/lecture', {params: {title: "lecture"}, timeout: $scope.defer.promise})
        .then(function(data) {
            $log.log(data)
        })*/
        
                
/*        $http(
            {
                url: '/lecture', 
                method: 'get', 
                params: {title: "lecture"}, 
                timeout: $scope.defer.promise
            }
        ).then(function(data) {
            $log.log(data)
        })*/
    }
    
    $scope.stop = function() {
        if ($scope.defer) {
            $scope.defer.resolve()
        }
    }
}]);