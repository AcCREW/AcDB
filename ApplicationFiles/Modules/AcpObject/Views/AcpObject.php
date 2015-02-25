<div ng-controller="AngularAcpObjectController">
    <h2>{{this.ObjectText}}</h2>
    <tabset>
        <tab heading="General">
            <div class="tab_content">
                <div class="row form-group">
                    <div class="col-md-6">
                        <label>Object name</label>
                        <input type="text" ng-model="$parent.this.ObjectName" class="form-control">
                     </div>
                    <div class="col-md-6">
                        <label>Object text</label>
                        <input type="text" ng-model="$parent.this.ObjectText" class="form-control">
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-12">
                        <a style="text-decoration: none!important;cursor: pointer!important;" ng-click="$parent.this.isAdvanceOptionsCollapse = !$parent.this.isAdvanceOptionsCollapse">Advanced options</a>
                    </div>
                </div>
                <div class="row form-group" collapse="$parent.this.isAdvanceOptionsCollapse">
                    <div class="col-md-12">
                        <label>Object database name</label>
                        <input type="text" ng-model="$parent.this.ObjectTableName" class="form-control">
                     </div>
                </div>
            </div>
        </tab>
                    
        <tab heading="Fields">
            <div class="tab_content">
                <accordion close-others="oneAtATime">
                    <accordion-group ng-repeat="Field in $parent.$parent.this.Fields">
                        <accordion-heading>
                            {{Field.Name}} <i class="pull-right glyphicon glyphicon-remove" style="z-index: 999;" ng-click="removeField(Field);"></i>
                        </accordion-heading>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Field name</label>
                                <input type="text" ng-model="Field.Name" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label>Field type</label>
                                 <select class="form-control" ng-model="Field.Type" ng-change="Field.ShowObjectPicker = Field.Type == 2 ? '' : 'hide'">
                                    <option value="1">varchar</option>
                                    <option value="2">foreignKey</option>
                                    <option value="3">text</option>
                                    <option value="4">int</option>
                                    <option value="5">datetime</option>
                                    <option value="6">time</option>
                                </select>                            </div>
                        </div>
                        <div class="row form-group {{Field.ShowObjectPicker}}">
                            <div class="col-md-6">
                            </div>
                            <div class="col-md-6">
                                <label>Object name</label>
                                <input type="text" ng-model="Field.ObjectName" class="form-control">
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-12">
                                <a style="text-decoration: none!important;cursor: pointer!important;" ng-click="Field.ShowAdvancedOptions = Field.ShowAdvancedOptions == 'hide' ? '' : 'hide'">Advanced options</a>
                            </div>
                        </div>
                        <div class="row form-group {{Field.ShowAdvancedOptions}}">
                            <div class="col-md-12">
                                <label>Decimals</label>
                                <input type="text" ng-model="Field.Decimals" class="form-control">
                             </div>
                        </div>
                    </accordion-group>
                </accordion>
                <div class="row form-group">
                    <div class="col-md-2">
                        <input type="button" value="Add Field" ng-click="addField()" class="form-control">
                    </div>
                </div>
            </div>
        </tab>
        <tab heading="Form">
            <div class="tab_content">
                Comming soon...
            </div>
        </tab>
    </tabset>
</div>
