'use strict';

/* Controllers */

angular.module('AngularLogin', [])
	.controller('AngularLoginController', function ($scope, $http, userService, AcHTTP, $window) {
		if (userService.AngularLogin === undefined) {
		    userService.AngularLogin = {
		        EMail: "",
		        Password: "",
		        LoginAlert: "",
		        ShowLoginAlert: "hide"
		    }
		}

		$scope._this = userService.AngularLogin;

		$scope.Save = function () {
		    AcHTTP.PostRequest(BaseURL + 'index.php', { "Module": 'Login', "Action": 1001, "EMail": $scope._this.EMail, "Password": $scope._this.Password }).then(function (_Data) {
		        if (_Data.Error == 1) {
		            $scope._this.LoginAlert = _Data.Message;
		            $scope._this.ShowLoginAlert = "";
		        } else {
		            $window.location.href = BaseURL;
		        }
		    }, function (Error) {
		        console.log(Error);
		    });
		};
});