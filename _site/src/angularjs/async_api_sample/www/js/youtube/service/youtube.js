"use strict";

angular.module('youtube').service('$youtube', ['$window', '$rootScope', '$log', '$youtubeReady', function ($window, $rootScope, $log, $youtubeReady) {

    var player = function (id, config) {

        // from YT.PlayerState
        this.stateNames = {
            0: 'ended',
            1: 'playing',
            2: 'paused',
            3: 'buffering',
            5: 'queued'
        };

        this._listeners = {
            'ready': []
        };

        for (var idx in this.stateNames) {
            this._listeners[this.stateNames[idx]] = []
        }
        var self = this;
        this.playerId = id;

        $youtubeReady.onReady(function() {
            $log.log('api ready')
            self.play()
        })

        this.config = angular.extend({
            height: this.playerHeight,
            width: this.playerWidth,
            videoId: this.videoId,
            playerVars: {
                'autoplay': 1
            }
        }, config || {});

        /*
         * Disable event listener overwright
         */
        this.config['events'] = {
            onReady: function (event) {
                self._broadcast('ready')
            },
            onStateChange: function(event) {
                if (typeof self.stateNames[event.data] !== 'undefined') {
                    self._broadcast(self.stateNames[event.data])
                }
            }
        }

    };

    player.prototype = angular.extend(player.prototype, {
        // Element id for player
        playerId: null,

        // Player currently in use
        player: null,

        // Current video id
        videoId: null,

        // Size
        playerHeight: '390',
        playerWidth: '640',

        currentState: null,

        _create: function () {
            this.player = new YT.Player(this.playerId, this.config);
            return this;
        },

        close: function() {
            if (this.player && typeof this.player.destroy === 'function') {
                this.player.destroy();
                this.player = null
            }
        },

        play: function () {
            if ($youtubeReady.ready && this.playerId && this.config.videoId) {
                if (! (this.player && typeof this.player.destroy === 'function')) {
                    this._create();
                } else {
                    this.player.loadVideoById(this.config.videoId)
                }
            }
            return this;
        },
        video: function(videoId) {
            this.config.videoId = videoId;
            return this;
        },

        on: function(event, callback) {
            if (this._listeners[event] instanceof Array) {
                this._listeners[event].push(callback);
            }
            return this;
        },

        /**
         * Call all listeners, that attached to event "name".
         *
         * "Name" is defined in stateNames array
         *
         * @param name
         * @private
         */
        _broadcast: function(name) {
            var self = this;
            $rootScope.$apply(function() {
                if (self._listeners[name] instanceof Array) {
                    for (var idx in self._listeners[name]) {
                        self._listeners[name][idx]()
                    }
                }
            })
        }
    });

    var service = function () {

    };

    service.prototype = angular.extend(service.prototype, {
        // Frame is ready
        _ready: false,

        _players: {},

        /**
         * Создает новый контейнер плеера с полученным id.
         * Помещает его в хранилище.
         *
         * @deprecated
         * @param id
         * @returns {*}
         */
        create: function(id) {
            if (this._players[id]) {
                return this._players[id];
            }

            return this._players[id] = new player(id);
        },
        /**
         * Закрывает окошко плеера с идентификатором id.
         * Используется при уничтожении скоупа.
         * Нужно когда плеер перестает быть видимым и должен быть выгружен со страницы.
         *
         * @param id
         */
        close: function(id) {
            this._players[id].close();
        },

        /**
         * Получение плеера с идентификатором id
         *
         * @param id
         * @returns {id}
         */
        get: function(id) {
            return this.create(id)
        }
    });

    return new service();
}]);