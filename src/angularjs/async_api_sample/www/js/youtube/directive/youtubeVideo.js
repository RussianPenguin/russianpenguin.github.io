"use strict";

angular.module('youtube').directive('youtubeVideo', ['$youtube', function ($youtube) {
    return {
        restrict: 'EA',
        scope: {
            videoId: '='
        },
        link: function (scope, element, attrs) {
            var id = element.attr('id');
            // Attach to element
            var player = $youtube.get(id)

            if (scope.videoId)
                player.video(scope.videoId).play();

            scope.$on('$destroy', function() {
                $youtube.close(id)
            });
        }
    };
}]);