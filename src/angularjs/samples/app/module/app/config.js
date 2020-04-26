angular.module("app").config(['$filterProvider', '$routeProvider', 'informerProvider', function($filterProvider, $routeProvider, $informerProvider) {
    
    $informerProvider.setLimit(2)
    
    $filterProvider.register('fruit2', function() {
        return function(items) {
	    filtered = []
            for (var i = 0; i < items.length; i++) {
        	if (items[i] != "wine") {
    	            filtered.push(items[i]);
	        }
            }
    	    return filtered
	}
    })
    
    $routeProvider.when('/', {
        templateUrl: '/tmpl/greeting.html'
    }).when('/week2', {
        templateUrl: '/tmpl/week2.html'
    }).when('/:chapter/description/:id', {
        templateUrl: '/tmpl/description.html',
        controller: 'description'
    }).when('/week2/location', {
        templateUrl: '/tmpl/location.html',
        controller: 'location'
    }).when('/week3/lectures', {
        templateUrl: '/tmpl/lectures.html',
        controller: 'lectures'
    }).when('/week3/promise', {
        templateUrl: '/tmpl/promise.html',
        controller: 'promise'
    }).when('/week3/http', {
        templateUrl: '/tmpl/http.html',
        controller: 'http'
    }).when('/week3/yandex', {
        templateUrl: '/tmpl/yandex.html',
        controller: 'yandex'
    }).when('/week3/binding', {
        templateUrl: '/tmpl/binding.html',
        controller: 'binding'
    })
}])