angularjs-gapi
====

Google JS API Angular wrapper.

This wrapper work with https://developers.google.com/api-client-library/javascript/reference/referencedocs library.

## Usage

### Init

```javascript
angular.module('app').config(['gapiProvider', '$routeProvider', function(gapiProvider, $routeProvider) {
	gapiProvider.apiKey(YOU_API_KEY) // api for app (you can create them in dev console)
        .clientId(YOU_APP_CLIENT_ID) // you can obtain them in gogle dev console
        .apiScope(SCOPES_FOR_APP); // scopes is space delimited string
}])
```

### login

```javascript
angular.module('app').controller('tstController', ['$scope', 'gapi', function($scope, gapi) {
	gapi.login().then(function() {
    $scope.login = 'success';
  }, function() {
    $scope.login = 'fail';
  });
}]);
```

### Make api requests

* Unauthorized request

```javascript

// see https://developers.google.com/youtube/v3/docs/search/list for more information
angular.module('app').controller('tstController', ['$scope', 'gapi', 'gapiModel', function($scope, gapi, api) {
    // we can get model for gapi request
    $scope.data = api.$find("youtube", "v3", "search", "list", {
        query: "search term",
        part: "snippet",
        type: "video"
    })

    // also we can query api again (for example: query another path with new pageToken)
    $scope.data.$query({pageToken: 'someToken'})

    // or we can call api directly
    gapi.call("youtube", "v3", "search", "list", {
      query: "search term",
      part: "snippet",
      type: "video"
    }).then(function(response) {
      // work with response
    })
}]);

```

* Authorized requests

```javascript
// see https://developers.google.com/youtube/v3/docs/playlists/list for information about api
angular.module('app').controller('tstController', ['$scope', 'gapi', function($scope, gapi) {
  // we can't make requests while api is not ready and user is not logged in
  if (gapi.isApiReady() && gapi.isLoggedIn()) {
  	gapi.call("youtube", "v3", "playlists", "list", {
      part: "snippet",
      type: "video"
    }).then(function(response) {
      // work with response
    })
  }
}]);
```

## Version history
### 0.1.0
* Simple gapi query for authorized and unauthorized requests
* Only one method gapi.call for get result

### 0.1.1
* Add model for wrap google api request (see https://www.youtube.com/watch?v=lHbWRFpbma4)