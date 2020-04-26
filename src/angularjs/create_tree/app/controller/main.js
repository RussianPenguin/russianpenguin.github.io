angular.module('app').controller('main', ['$scope', '$log', 'builder', function($scope, $log, builder) {
    $scope.src = [
        {
            type: "place",
            name: "garden",
            trees: [
                {
                    type: "tree",
                    name: "apple",
                    food: [
                        {
                            type: "food",
                            name: "apple",
                            rotten: true
                        },
                        {
                            type: "food",
                            name: "red apple",
                            colour: "red"
                        },
                        {
                            type: "food",
                            name: "yellow apple"
                        }
                    ]
                }
            ],
            potato: {
                type: "food",
                name: "big cluster of potato"
            }
        },
        {
            type: "place",
            name: "kitchen-garden",
            tomato: {
                type: "food",
                name: "tomato"
            },
            compost: {
                type: "place",
                name: "compost",
                coords: { // тестируем координаты
                    lat: 0.12,
                    lon: 0.12
                },
                components: [
                    {
                        type: "food",
                        name: "apple",
                        rotten: false
                    }
                ]
            }
        }
    ];

    $scope.models = null;

    $scope.build = function() {
        $scope.models = builder.build($scope.src);
        $log.log($scope.models)
    }
}]);