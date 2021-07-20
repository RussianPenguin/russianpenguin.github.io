"use strict";

angular.module('youtube').run(['$window', '$rootScope', '$youtubeReady', function ($window, $rootScope, $youtubeReady) {
    var tag = document.createElement('script');
    tag.src = "//www.youtube.com/iframe_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

    // Youtube callback when API is ready
    $window.onYouTubeIframeAPIReady = function () {
        $rootScope.$apply(function () {
            $youtubeReady.setReady();
        });
    };
}]);