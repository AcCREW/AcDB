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
}).service('AcHTTP', function ($http) {
    this.Request = function (sURL, vData) {
        var request = $http({
            method: 'GET',
            url: sURL,
            params: vData,
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        });

        return (request.then(function (response) { return response.data }, function (response) { return new Error(response.statusText, response.status); }));
    }

    this.PostRequest = function (sURL, vData) {
        eval("var Obj = { " + CSRFTokenName + " : '" + CSRFTokenValue + "' }");

        var request = $http({
            method: 'POST',
            url: sURL,
            data: $.param($.extend({}, Obj, vData)),
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        });

        return (request.then(function (response) { return response.data }, function (response) { return new Error(response.statusText, response.status); }));
    }
}).directive('focusMe', function($timeout, $parse) {
    return {
        //scope: true,   // optionally create a child scope
        link: function(scope, element, attrs) {
            var model = $parse(attrs.focusMe);
            scope.$watch(model, function(value) {
                if(value === true) { 
                    $timeout(function() {
                        element[0].focus(); 
                    });
                }
            });
        }
    };
}).config(['$ocLazyLoadProvider', function ($ocLazyLoadProvider) {
    $ocLazyLoadProvider.config({
        loadedModules: [],
        jsLoader: requirejs,
        debug: false
    });
}]).controller('AcController', ['$scope', '$ocLazyLoad', '$http', 'ngProgress', 'AcHTTP', function ($scope, $ocLazyLoad, $http, ngProgress, AcHTTP) {
    $scope.RightContent = "";
    $scope.arHTMLCache = Array();
    $scope.CurrentModule = "AcpObject"; // defualt Index
    $scope.LoginMode = false;

    $scope.$watch('CurrentModule', function (sNewModule, sOldModule) {
        if ($scope.LoginMode) {
            return;
        }
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
        AcHTTP.Request(BaseURL + 'index.php', { "Module": sModule, "Action": 1000 }).then(function (_Data) {
            sModule = _Data.Module;
            if (sModule == 'Login') {
                $scope.LoginMode = true;
            }
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
