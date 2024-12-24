---
layout: post
title: Google JS API для AngularJS
type: post
categories:
- Разработка
tags:
- angularjs
- google
- javascript
permalink: "/2014/11/08/google-js-api-%d0%b4%d0%bb%d1%8f-angularjs/"
---
Написал небольшой враппер к гугловому апи для использования совместно с ангуляром. Взять можно на [гитхабе](https://github.com/RussianPenguin/angularjs-gapi "angularjs-gapi").

Или в bower

```shell
 $ bower install angularjs-gapi
```

### Поключение

```javascript
angular.module('app', ['gapi']).config(['gapiProvider', '$routeProvider', function(gapiProvider, $routeProvider) {  
 gapiProvider.apiKey(YOU_API_KEY) // апи-ключ можно создать в консоли разработчика  
 .clientId(YOU_APP_CLIENT_ID) // берем в консоли разработчика  
 .apiScope(SCOPES_FOR_APP); // скоупы, которые нужны для работы приложения  
}])
```

Если вам не требуется работать с пользовательскими данными и не требуется разрешение пользователя, то достаточно использования только YOU_API_KEY.

### Авторизация

```javascript
angular.module('app').controller('tstController', ['$scope', 'gapi', function($scope, gapi) {  
 gapi.login().then(function() {  
 $scope.login = 'success';  
 }, function() {  
 $scope.login = 'fail';  
 });  
}])
```

### Выполнение запросов не требующих авторизации

```javascript
angular.module('app').controller('tstController', ['$scope', 'gapi', function($scope, gapi) {  
 // we can't make requests while api is not ready  
 if (gapi.isApiReady()) {  
 gapi.call("youtube", "v3", "search", "list", {  
 query: "search term"  
 part: "snippet"  
 type: "video"  
 }).then(function(response) {  
 // work with response  
 })  
 }  
}]);
```

### Выполнение запросов требующих авторизации

```javascript
angular.module('app').controller('tstController', ['$scope', 'gapi', function($scope, gapi) {  
 // we can't make requests while api is not ready and user is not logged in  
 if (gapi.isApiReady() && gapi.isLoggedIn()) {  
 gapi.call("youtube", "v3", "playlists", "list", {  
 part: "snippet",  
 type: "video";  
 }).then(function(response) {  
 // work with response  
 })  
 }  
}]);
```

Вроде все.

