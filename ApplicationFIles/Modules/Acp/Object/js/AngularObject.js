'use strict';

/* Controllers */

angular.module('AngularObject', [])
	.controller('AngularObjectController', function ($scope, $http) {
		$scope.ObjectID = null;
		$scope.ObjectText = "New Object";
		$scope.ObjectName = "NewObject";
		$scope.ObjectTableName = "";

		$scope.Fields = [{ "Name": "ObjectID", "Type": "", "ShowObjectPicker": "hide", "Object": "", "Decimals": "2" }];

		$scope.$watch('ObjectName', function (newValue, oldValue) {
			newValue = newValue.replace(/\s/g, '');

			$scope.ObjectName = newValue;

			if ($scope.ObjectTableName == "" || $scope.ObjectTableName == oldValue) {
				$scope.ObjectTableName = newValue;
			}
		});

		$scope.$watchCollection('Fields', function (newCollection, oldCollection) {
			//TODO: това е яко тъпо трябва да се измисли нов начин
			$scope.$evalAsync(function () {
				//$.Metro.initListViews();
			});
		});

		$scope.onTypeChange = function (Field) {
			Field.ShowObjectPicker = Field.Type == 2 ? "" : "hide";
		};

		$scope.addField = function () {
			$scope.Fields.push({ "Name": "NewField", "Type": "", "ShowObjectPicker": "hide", "Object": "", "Decimals": "2" });
		};

		$scope.removeField = function (Field) {
			var i = $scope.Fields.indexOf(Field);
			$scope.Fields.splice(i, 1);
		};

		$scope.Save = function () {
			var sURL = '/AcGenerator/Generator.php';
			var vData = {
				ObjectID: $scope.ObjectID,
				Fields: $scope.Fields,
				ObjectName: $scope.ObjectName,
				ObjectTableName: $scope.ObjectTableName,
				ObjectText: $scope.ObjectText,
				InitData: true
			};
			$scope.Request(sURL, vData).then(function (data) { $("#Dump").html(data); }, function (Error) { console.log(Error); });
		};

		$scope.Request = function (sURL, vData) {
			var request = $http({
				method: 'POST',
				url: sURL,
				data: $.param(vData),
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
			});

			return (request.then(function (response) { return response.data }, function (response) { return new Error(response.statusText, response.status); }));
		}
	});