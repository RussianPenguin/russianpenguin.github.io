'use strict';

angular.module('gapi').factory('gapiModel', ['$timeout', 'gapi', '$log', function($timeout, gapi, $log) {
    /**
     * item model can work with promise and immediately available futureItemData.
     *
     */
    var Item = function Item(futureItemData) {
        /**
         * If futureItemData is not promise then fill model immediately.
         */
        if (futureItemData.$$state) {
            this.$unwrap(futureItemData);
        } else {
            _.extend(this, futureItemData);
        }
    };

    /**
     * Factory method.
     * @see gapi.call for more information.
     * This method only wrap call action in usable form.
     *
     * @param name API name for find action (example: youtube)
     * @param version API version (example: v3)
     * @param scope application scope (example: playlists)
     * @param action action in scope (example list)
     * @param options extended options (example {mine: true, part: snippet})
     */
    Item.$find = function(name, version, scope, action, options) {
        // get promise for query
        var futureItemData = gapi.call(name, version, scope, action, options);
        // return model with promise waiting
        var item =  new Item(futureItemData);
        // save options for later
        // we can use them in $query function
        item.callOptions(name, version, scope, action, options);
        return item;
    };

    /**
     * Fill model from futureItemData (promise only)
     * @param futureItemData
     */
    Item.prototype.$unwrap = function(futureItemData) {
        var self = this;
        this.$futureItemData = futureItemData;
        this.$futureItemData.then(function(data) {
            $timeout(function() {
                _.extend(self, data);
            })
        })
    };

    /**
     * Save $options for later (we can use them in $query function
     * @param name API name for find action (example: youtube)
     * @param version API version (example: v3)
     * @param scope application scope (example: playlists)
     * @param action action in scope (example list)
     * @param options extended options (example {mine: true, part: snippet})
     */
    Item.prototype.callOptions = function options(name, version, scope, action, options) {
        this.$apiOptions = {
            'name': name,
            'version': version,
            'scope': scope,
            'action': action,
            'options': options
        }
    };

    Item.prototype.$query = function($options) {
        var options = this.$apiOptions.options || {};

        _.extend(options, $options || {});

        var futureItemData = gapi.call(
            this.$apiOptions.name,
            this.$apiOptions.version,
            this.$apiOptions.scope,
            this.$apiOptions.action,
            options
        );

        this.$unwrap(futureItemData);
    };

    return Item;
}]);