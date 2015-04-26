<div ng-controller="AngularLoginController" style="margin: 0 auto; width: 500px;margin-top: 20px;border: 1px solid #ccc;padding: 10px;">
    <form>
        <div class="alert alert-danger {{_this.ShowLoginAlert}}" role="alert">{{_this.LoginAlert}}</div> 
        <div class="form-group">
            <label for="exampleInputEmail1">EMail</label>
            <input type="email" class="form-control" focus-me="true" placeholder="Enter email" data-ng-model="_this.EMail">
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input type="password" class="form-control" placeholder="Password" data-ng-model="_this.Password">
        </div>
        <button type="submit" class="btn btn-default" data-ng-click="Save()">Submit</button>
    </form>
</div>
