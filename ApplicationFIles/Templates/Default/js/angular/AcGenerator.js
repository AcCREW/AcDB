angular.module('AcGenerator', ['oc.lazyLoad'])
	.config(['$ocLazyLoadProvider', function ($ocLazyLoadProvider) {
		$ocLazyLoadProvider.config({
			loadedModules: [],
			jsLoader: requirejs,
			debug: true
		});
	}]).controller('AcController', ['$scope', '$ocLazyLoad', function ($scope, $ocLazyLoad) {
		$scope.Text = "asd";

	}]);
