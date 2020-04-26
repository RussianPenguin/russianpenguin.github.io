"use strict";

angular.module('app').controller("PlayerController", ['$youtube', '$scope', '$youtubeReady', '$log', function($youtube, $scope, $youtubeReady, $log) {

    $scope.ids = [
        '4C4bmDOV5hk',
        'uD6Okha_Yj0',
        'dF_ObGgzGE8'
    ];

    $scope.videoId = 'dF_ObGgzGE8';
    $scope.videoId1 = $scope.ids[1];

    $scope.$watch('videoId1', function(val) {
        $youtube.get('p2').video(val).on('paused', function() {
            console.log("event");
            if (!$scope.$$phase)
                $scope.$digest();
        }).play()
    });

    var current = 0;
    $youtube.get('p1').on('ended', function() {
        $youtube.get('p1').video($scope.ids[++current % $scope.ids.length]).play()
    });

    $scope.playSome = function () {
        $youtube.get('p1').video($scope.videoId).play();
    };

    $scope.playAgain = function() {
        $youtube.get('p1').play();
    }

    $youtube.get('p1').on('paused', function() {
        console.log("paused");
        if (!$scope.$$phase)
            $scope.$digest();
    })
}]);