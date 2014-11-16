<fieldset ng-controller="{Module}">
    <legend>{{ObjectText}}</legend>
    <div class="tab-control" data-role="tab-control">
        <ul class="tabs">
            <li><a href="#new_object_general_information">General</a></li>
            <li class="active"><a href="#new_object_fields">Fields</a></li>
            <li><a href="#new_object_form_contructor">Form</a></li>
            <li class="place-right disabled" ng-click="Save();"><a href="#" class="disabled"><i class="fa fa-save"></i>Save</a></li>
        </ul>

        <div class="frames">
            <div id="new_object_general_information" class="frame">
                <div class="grid fluid">
                    <div class="row" style="margin-top: 0;">
                        <div class="span6">
                            <label>Object name</label>
                            <div class="input-control text" data-role="input-control">
                                <input type="text" placeholder="type text" ng-model="ObjectName">
                                <button class="btn-clear" tabindex="-1"></button>
                            </div>
                        </div>
                        <div class="span6">
                            <label>Object text</label>
                            <div class="input-control text" data-role="input-control">
                                <input type="text" placeholder="type text" ng-model="ObjectText">
                                <button class="btn-clear" tabindex="-1"></button>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 0;">
                        <div class="listview" data-role="listview">
                            <div class="list-group collapsed">
                                <a href="" class="group-title">Advanced option</a>
                                <div class="group-content" style="padding: 10px; background-color: rgb(240, 240, 240);">
                                    <fieldset>
                                        <label>Object database name</label>
                                        <div class="input-control text" data-role="input-control">
                                            <input type="text" placeholder="type text" ng-model="ObjectTableName">
                                            <button class="btn-clear" tabindex="-1"></button>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="new_object_fields" class="frame">
                <div class="accordion with-marker span12 place-left margin10" data-role="accordion" data-closeany="true">
                    <div class="accordion-frame" ng-repeat="Field in Fields">
                        <a class="heading active collapsed" href="#">{{Field.Name}}<i ng-click="removeField(Field)" class="fa fa-remove place-right"></i></a>
                        <div class="content">
                            <div class="grid fluid">
                                <div class="row" style="margin-top: 0;">
                                    <div class="span6">
                                        <label>Field name ({{Field.ID}})</label>
                                        <div class="input-control text" data-role="input-control">
                                            <input type="text" placeholder="type text" ng-model="Field.Name">
                                            <button class="btn-clear" tabindex="-1"></button>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <label>Field type ({{Field.Type}})</label>
                                        <div class="input-control select">
                                            <select ng-model="Field.Type" ng-change="onTypeChange(Field);">
                                                <option value="1">varchar</option>
                                                <option value="2">foreignKey</option>
                                                <option value="3">text</option>
                                                <option value="4">int</option>
                                                <option value="5">datetime</option>
                                                <option value="6">time</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="grid fluid {{Field.ShowObjectPicker}}">
                                    <div class="row" style="margin-top: 0;">
                                        <div class="span6">
                                        </div>
                                        <div class="span6">
                                            <label>Object Name</label>
                                            <div class="input-control text" data-role="input-control">
                                                <input type="text" placeholder="type text" ng-model="Field.Object">
                                                <button class="btn-clear" tabindex="-1"></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 0;">
                                    <div class="listview" data-role="listview" ng-click="UpdateListView(Field)">
                                        <div class="list-group collapsed">
                                            <a href="" class="group-title">Advanced option</a>
                                            <div class="group-content" style="padding: 10px; background-color: rgb(240, 240, 240);">
                                                <fieldset>
                                                    <label>Decimals</label>
                                                    <div class="input-control text" data-role="input-control">
                                                        <input type="text" placeholder="type text" ng-model="Field.Decimals">
                                                        <button class="btn-clear" tabindex="-1"></button>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="toolbar transparent" style="margin-top: 10px;">
                        <button ng-click="addField()"><i class="fa fa-plus"></i>Add field</button>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
            <div id="new_object_form_contructor" class="frame">
            </div>
        </div>
    </div>
</fieldset>
<div id="Dump"></div>
