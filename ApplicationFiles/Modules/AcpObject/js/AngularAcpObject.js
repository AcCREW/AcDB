'use strict';

angular.module('AngularAcpObject', ['ui.bootstrap'])
	.controller('AngularAcpObjectController', function ($scope, $http, userService, $window) {
	    if (userService.AngularAcpObject === undefined) {
	        userService.AngularAcpObject = {
	            'ObjectText': 'New Object',
	            'ObjectName': 'NewObject',
	            'isAdvanceOptionsCollapse': true,
	            'ObjectID': null,
	            'ObjectTableName': '',
	            'Fields': [{ "Name": "ObjectID", "Type": "", "ShowObjectPicker": "hide", "Object": "", "ShowAdvancedOptions": "hide", "Decimals": "2" }]
	        };
	    }

	    $scope.this = userService.AngularAcpObject;

	    $scope.$watch('this.ObjectName', function (newValue, oldValue) {
	        newValue = newValue.replace(/\s/g, '');

	        $scope.this.ObjectName = newValue;

	        if ($scope.this.ObjectTableName == "" || $scope.this.ObjectTableName == oldValue) {
	            $scope.this.ObjectTableName = newValue;
	        }
	    });

	    $scope.$watchCollection('this.Fields', function (newCollection, oldCollection) {
	    });

	    $scope.onTypeChange = function (Field) {
	        Field.ShowObjectPicker = Field.Type == 2 ? "" : "hide";
	    };

	    $scope.addField = function () {
	        $scope.this.Fields.push({ "Name": "NewField", "Type": "", "ShowObjectPicker": "hide", "Object": "", "ShowAdvancedOptions": "hide", "Decimals": "2" });
	    };

	    $scope.removeField = function (Field) {
	        var i = $scope.this.Fields.indexOf(Field);
	        $scope.this.Fields.splice(i, 1);
	    };

	    $scope.Save = function () {
	        var sURL = '/AcGenerator/Generator.php';
	        var vData = {
	            ObjectID: $scope.this.ObjectID,
	            Fields: $scope.this.Fields,
	            ObjectName: $scope.this.ObjectName,
	            ObjectTableName: $scope.this.ObjectTableName,
	            ObjectText: $scope.this.ObjectText,
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