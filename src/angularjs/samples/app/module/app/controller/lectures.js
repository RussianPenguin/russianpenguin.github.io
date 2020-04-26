angular.module("app").controller("lectures", ['$scope', '$resource', function($scope, $resource) {
    var Lectures = $resource('/lecture/:id', {id: '@id'});
    
    $scope.lectures = Lectures.query();
    
    $scope.query = '';
    
    $scope.$watch('query', function(newVal, oldVal) {
        $scope.lectures = Lectures.query({title: newVal});
    })
    
    $scope.delete = function(lecture) {
        lecture.$delete();
        $scope.lectures = Lectures.query();
    }
    
    $scope.lecture = {}
    
    $scope.save = function() {
        if ($scope.newLecture.$valid) {
            Lectures.save($scope.lecture);
            $scope.lectures = Lectures.query();
            $scope.lecture = {}
        }
    }
}])