'use strict';

/* Controllers */

angular.module('AngularIndex', [])
	.controller('AngularIndexController', function ($scope, $http, userService) {
		if (userService.AngularIndex === undefined) {
		    userService.AngularIndex = {
		        Text: "AngularIndex",
                ObjectText: "AngularIndex"
		    }
		}

		$scope._this= userService.AngularIndex;
});