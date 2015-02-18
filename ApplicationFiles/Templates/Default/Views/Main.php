<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{SiteTitle}</title>
    {PreloadedCSS}<link rel="stylesheet" href="{Link}" />
    {/PreloadedCSS}
    <script type="text/javascript" src="{BaseURL}ApplicationFiles/Templates/Default/js/angular/require.js" charset="UTF8"></script>
    <script>
        var BaseURL = "{BaseURL}";
        requirejs.config({
            baseUrl: 'js/',
            paths: {PreloadedJS},
            shim: {PreloadedJSScheme}
        });

        requirejs(['AcGenerator'], function () {
            angular.bootstrap(document.body, ['AcGenerator']);
        });
	   
    </script>
</head>
<body data-ng-controller="AcController">
    <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#" style="width: 200px;">AcDB</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li><a href="#" ng-click="GoTo('Index');">Index<span class="sr-only">(current)</span></a></li>
                    <li><a href="#" ng-click="GoTo('AcpObject');">Object</a></li>
                    <li><a href="#" ng-click="GoTo('AcpUpdate');">Update</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Dropdown <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#">Action</a></li>
                            <li><a href="#">Another action</a></li>
                            <li><a href="#">Something else here</a></li>
                            <li class="divider"></li>
                            <li><a href="#">Separated link</a></li>
                            <li class="divider"></li>
                            <li><a href="#">One more separated link</a></li>
                        </ul>
                    </li>
                </ul>
                <form class="navbar-form navbar-left" role="search">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Search">
                    </div>
                    <button type="submit" class="btn btn-default">Submit</button>
                </form>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="#">Link</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Dropdown <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#">Action</a></li>
                            <li><a href="#">Another action</a></li>
                            <li><a href="#">Something else here</a></li>
                            <li class="divider"></li>
                            <li><a href="#">Separated link</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>
    <div class="AcContainer container-fluid" compile="RightContent">
    </div>
    <div class="AcLeftNavigation">
        a<br />
        a<br />
        a<br />
        a<br />
        a<br />
        a<br />
        a<br />
        a<br />
        a<br />
        a<br />
    </div>
</body>
</html>
