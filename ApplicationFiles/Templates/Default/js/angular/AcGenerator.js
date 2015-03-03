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
    $scope.CurrentModule = "AcpObject"; // defualt Index

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
        $scope._Load(sNewModule);
    });

    $scope.GoTo = function (sModule) {
        $scope.CurrentModule = sModule;
    }

    $scope._Load = function (sModule) {
        if (sModule == "") {
            return;
        }
        
        if ($scope.arHTMLCache[sModule] !== undefined) {
            var _Data = $scope.arHTMLCache[sModule];
            $scope.RightContent = _Data.Content;
            document.title = _Data.SiteTitle + ' - ' + _Data.ModuleTitle;

            return;
        }

        ngProgress.reset().start();
        $scope.Request(BaseURL + 'index.php', { "Module": sModule, "Action": 1000 }).then(function (_Data) {
            $ocLazyLoad.load({
                name: 'Angular' + sModule,
                files: ['../ApplicationFiles/Modules/' + sModule + '/js/Angular' + sModule]
            }).then(function () {
                $scope.RightContent = _Data.Content;
                $scope.arHTMLCache[sModule] = _Data;
                document.title = _Data.SiteTitle + ' - ' + _Data.ModuleTitle;
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
