angular.module('app').provider('informer', function() {
    var limit = 10;
    
    var $informerFn = function() {
        this.changeHistory = [
        ]

        this.change = function(item) {
            if (this.changeHistory.length == limit) {
                this.changeHistory.splice(0, 1);
            }
            this.changeHistory.push(item);
        }
    }
    
    this.setLimit = function(newLimit) {
        limit = newLimit;
    }
    
    this.$get = function() {
        return new $informerFn();
    }
})

