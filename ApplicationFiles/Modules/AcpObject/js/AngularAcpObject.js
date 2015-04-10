'use strict';

angular.module('AngularAcpObject', ['ui.bootstrap'])
	.controller('AngularAcpObjectController', function ($scope, $http, userService, $window) {
	    if (userService.AngularAcpObject === undefined) {
	        userService.AngularAcpObject = {};
	        userService.AngularAcpObject.HasBusinessLogicOptions = [
                    { value: '1', text: 'Yes', disable: false },
                    { value: '0', text: 'No', disable: false }
	        ];
	        userService.AngularAcpObject.DefaultObjectMethodOptions = [
                    { value: 'overview', text: 'Overview', disable: false },
                    { value: 'list', text: 'List', disable: false },
	                { value: 'add', text: 'Add', disable: false }
	        ];
	        userService.AngularAcpObject.OverviewTypeOptions = [
                    { value: 'default', text: 'Default', disable: false },
                    { value: 'custom', text: 'Custom', disable: false }
	        ];

	        userService.AngularAcpObject.ObjectText = 'New Object';
	        userService.AngularAcpObject.ObjectName = 'NewObject';
	        userService.AngularAcpObject.isAdvanceOptionsCollapse = false, // default true
	        userService.AngularAcpObject.ObjectID = null;
	        userService.AngularAcpObject.ObjectTableName = '';
	        userService.AngularAcpObject.HasBusinessLogic = userService.AngularAcpObject.HasBusinessLogicOptions[0];
	        userService.AngularAcpObject.DefaultObjectMethod = userService.AngularAcpObject.DefaultObjectMethodOptions[0];
	        userService.AngularAcpObject.OverviewType = userService.AngularAcpObject.OverviewTypeOptions[0];
	        userService.AngularAcpObject.OverviewTemplate = 'Omg';
	        userService.AngularAcpObject.Fields = [{ "Name": "ObjectID", "Type": "", "ShowObjectPicker": "hide", "Object": "", "ShowAdvancedOptions": "hide", "Decimals": "2" }];
	    }

	    $scope._this = userService.AngularAcpObject;

	    $scope.$watch('_this.ObjectName', function (newValue, oldValue) {
	        //newValue = newValue.replace(/\s/g, '');

	        $scope._this.ObjectName = newValue;

	        if ($scope._this.ObjectTableName == "" || $scope._this.ObjectTableName == oldValue) {
	            $scope._this.ObjectTableName = newValue;
	        }
	    });

	    $scope.$watch('_this.HasBusinessLogic', function (newValue, oldValue) {
	        var bHasBusinessLogic = newValue.value == 1;
	        $scope._this.OverviewTypeOptions[0].disable = !bHasBusinessLogic;
	        $scope._this.DefaultObjectMethodOptions[1].disable = !bHasBusinessLogic;
	        $scope._this.DefaultObjectMethodOptions[2].disable = !bHasBusinessLogic;
	        if (!bHasBusinessLogic) {
	            $scope._this.OverviewType = $scope._this.OverviewTypeOptions[1];
	            $scope._this.DefaultObjectMethod = $scope._this.DefaultObjectMethodOptions[0];
            }
	    });

	    $scope.onTypeChange = function (Field) {
	        Field.ShowObjectPicker = Field.Type == 2 ? "" : "hide";
	    };

	    $scope.addField = function () {
	        $scope._this.Fields.push({ "Name": "NewField", "Type": "", "ShowObjectPicker": "hide", "Object": "", "ShowAdvancedOptions": "hide", "Decimals": "2" });
	    };

	    $scope.removeField = function (Field) {
	        var i = $scope._this.Fields.indexOf(Field);
	        $scope._this.Fields.splice(i, 1);
	    };

	    $scope.Save = function () {
	        var sURL = '/AcGenerator/Generator.php';
	        var vData = {
	            ObjectID: $scope._this.ObjectID,
	            Fields: $scope._this.Fields,
	            ObjectName: $scope._this.ObjectName,
	            ObjectTableName: $scope._this.ObjectTableName,
	            ObjectText: $scope._this.ObjectText,
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