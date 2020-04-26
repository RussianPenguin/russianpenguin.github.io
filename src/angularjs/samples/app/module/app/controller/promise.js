angular.module('app').controller('promise', ['$scope', '$q', '$log', function($scope, $q, $log) {
    var p1 = $q.defer()
    
    p1.promise.then(function(msg) {
        $log.log("Нажата кнопка 11 " + msg)
        return $q.resolve()
    }, function() {
        $log.log("Нажата кнопка 12")
    }, function() {
        $log.log("Нажата кнопка 13")
    }).then(function() {
        $log.log("Обещание исполнено")
    }, function() {
        $log.log("Обещание не исполнено")
    })
    
    var p2 = $q.defer()
    
    p2.promise.then(function() {
        $log.log("Нажата кнопка 21")
    })
    
    var p3 = $q.all([p1.promise, p2.promise])
    p3.then(function() {
        $log.log("Нажата кнопка 12 и 22")
    })
    
    $scope.button11click =  function() {
        p1.resolve("Обещание исполнено")
    }
    
    $scope.button12click =  function() {
        p1.reject()
    }
    
    $scope.button13click =  function() {
        p1.notify("notify")
    }
    
    $scope.button21click = function() {
        p2.resolve()
    }
}]);