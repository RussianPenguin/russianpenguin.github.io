"use strict";

angular.module('youtube').service('$youtubeReady', function () {
    var service = function() {
        this.ready = false

        this._listeners = []

        this.setReady = function() {
            if (this.ready) {
                return;
            }
            this.ready = true
            this._broadcast()
        }

        /**
         * Добавляет слушателя события готовности youtube-api к работе.
         * @param function listener
         */
        this.onReady = function(listener) {
            this._listeners.push(listener);
        }

        this._broadcast = function() {
            for (var idx in this._listeners) {
                this._listeners[idx]()
            }
        }
    }

    return new service();
});
