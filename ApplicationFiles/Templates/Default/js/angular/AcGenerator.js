angular.module('AcGenerator', ['oc.lazyLoad', 'ngProgress'], function ($compileProvider) {
    $compileProvider.directive('compile', function ($compile) {
        return function (scope, element, attrs) {
            scope.$watch(
			  function (scope) {
			      return scope.$eval(attrs.compile);
			  },
			  function (value) {
			      element.html(value);
			      $compile(element.contents())(scope);
			  }
			);
        };
    });
}).factory('userService', function () {
    var _UserService = {};

    return _UserService;
}).config(['$ocLazyLoadProvider', function ($ocLazyLoadProvider) {
    $ocLazyLoadProvider.config({
        loadedModules: [],
        jsLoader: requirejs,
        debug: false
    });
}]).controller('AcController', ['$scope', '$ocLazyLoad', '$http', 'ngProgress', function ($scope, $ocLazyLoad, $http, ngProgress) {
    $scope.RightContent = "";
    $scope.arHTMLCache = Array();
    $scope.CurrentModule = "";

    $scope.Request = function (sURL, vData) {
        var request = $http({
            method: 'GET',
            url: sURL,
            params: vData,
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        });

        return (request.then(function (response) { return response.data }, function (response) { return new Error(response.statusText, response.status); }));
    }

    $scope.$watch('CurrentModule', function (sNewModule, sOldModule) {
        $scope._Load(sNewModule, sOldModule);
    });

    $scope.GoTo = function (sModule) {
        $scope.CurrentModule = sModule;
    }

    $scope._Load = function (sNewModule, sOldModule) {
        if (sNewModule == "") {
            return;
        }
        if (sOldModule != "") {
            $scope.arHTMLCache[sOldModule] = $scope.RightContent;
        }
        if ($scope.arHTMLCache[sNewModule] !== undefined) {
            $ocLazyLoad.load({
                name: 'Angular' + sNewModule,
                files: ['../ApplicationFiles/Modules/' + sNewModule + '/js/Angular' + sNewModule]
            }).then(function () {
                $scope.RightContent = $scope.arHTMLCache[sNewModule];
            }, function (e) {
                console.log(e);
            });

            return;
        }

        ngProgress.reset().start();
        $scope.Request(BaseURL + 'index.php', { "Module": sNewModule, "Action": 1000 }).then(function (data) {
            $ocLazyLoad.load({
                name: 'Angular' + sNewModule,
                files: ['../ApplicationFiles/Modules/' + sNewModule + '/js/Angular' + sNewModule]
            }).then(function () {
                $scope.RightContent = data.Content;
                $scope.arHTMLCache[sNewModule] = data.Content;
                ngProgress.complete();
            }, function (e) {
                ngProgress.complete();
                console.log(e);
            });
        }, function (Error) {
            console.log(Error);
        });
    }
}]);
