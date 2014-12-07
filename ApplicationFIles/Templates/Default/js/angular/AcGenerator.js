angular.module('AcGenerator', ['oc.lazyLoad'], function ($compileProvider) {
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
}).config(['$ocLazyLoadProvider', function ($ocLazyLoadProvider) {
	$ocLazyLoadProvider.config({
		loadedModules: [],
		jsLoader: requirejs,
		debug: true
	});
}]).controller('AcController', ['$scope', '$ocLazyLoad', '$http', function ($scope, $ocLazyLoad, $http) {
	$scope.RightContent = "";

	$scope.Request = function (sURL, vData) {
		var request = $http({
			method: 'GET',
			url: sURL,
			params: vData,
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
		});

		return (request.then(function (response) { return response.data }, function (response) { return new Error(response.statusText, response.status); }));
	}

	$scope.load = function () {
		var sURL = BaseURL + 'index.php';
		var vData = {
			"Module": "Index",
			"Action": 1000
		};
		$scope.Request(sURL, vData).then(function (data) {
		$ocLazyLoad.load({
			name: 'AngularIndex',
			files: ['AngularIndex']
		}).then(function () {
			$scope.RightContent = data.Content;
		}, function (e) {
			console.log(e);
		});

		}, function (Error) {
			console.log(Error);
		});


	}

	$scope.load2 = function () {
		var sURL = BaseURL + 'index.php';
		var vData = {
			"Module": "Acp/Object",
			"Action": 1000
		};
		$scope.Request(sURL, vData).then(function (data) {
			$ocLazyLoad.load({
				name: 'AngularObject',
				files: ['../ApplicationFiles/Modules/Acp/Object/js/AngularObject']
			}).then(function () {
				$scope.RightContent = data.Content;
			}, function (e) {
				console.log(e);
			});

		}, function (Error) {
			console.log(Error);
		});


	}
}]);
