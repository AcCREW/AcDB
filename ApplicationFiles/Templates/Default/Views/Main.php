<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elemdnts and media queries -->
    <!--[if lt IE 9]>
      <script src="{BaseURL}ApplicationFiles/Templates/Default/js/libraries/html5shiv.min.js"></script>
      <script src="{BaseURL}ApplicationFiles/Templates/Default/js/libraries/respond.min.js"></script>
    <![endif]-->
</head>
<body data-ng-controller="AcController">
    <div class="main-wrap">
        <nav class="navbar navbar-static-top dash-navbar-top dnl-hidden" id="TopNav">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#dnt-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="fa fa-ellipsis-v"></span>
                    </button>
                    <button class="dnl-btn-toggle">
                        <span class="fa fa-bars"></span>
                    </button>
                    <a class="navbar-brand" href="#">AcDB <span><i>beta</i></span></a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="dnt-collapse">
                    <!-- This dropdown is for avatar -->
                    <ul class="nav navbar-nav navbar-right navbar-avatar">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <img src="./UserFiles/avatar.jpg" class="dnt-avatar" alt="Reardestani avatar"></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="#">Standard <span>go pro</span></a></li>
                                <li><a href="#">Upload</a></li>
                                <li class="active"><a href="#">Active link</a></li>
                                <li><a href="#">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                    <form class="navbar-form navbar-right dnt-navbar-form" role="search">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Search">
                        </div>
                        <button type="submit" class="btn"><span class="fa fa-search"></span></button>
                    </form>
                    <!-- This dropdown is for normal links -->
                    <ul class="nav navbar-nav navbar-right">
                        <li><a ng-click="GoTo('Index');">Index</a></li>
                        <li><a ng-click="GoTo('AcpObject');">Object</a></li>
                        <li><a ng-click="GoTo('AcpUpdate');">Update</a></li>
                        <li><a href="#">Support</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Dropdown <span class="fa fa-angle-down"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="#">Link 1 <span>LOL</span></a></li>
                                <li><a href="#">Link 2</a></li>
                                <li><a href="#">Link 3</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <!-- /.navbar-collapse -->
            </div>
            <!-- /.container-fluid -->
        </nav>
        <!-- /.navbar -->

        <!-- Dash Navbar Left 
        Available versions: dnl-visible, dnl-hidden -->
        <div class="dash-navbar-left dnl-hidden dnl-hide">
            <p class="dnl-nav-title">Home</p>
            <ul class="dnl-nav">
                <li>
                    <a class="collapsed" data-toggle="collapse" href="#collapseStatistics" aria-expanded="false" aria-controls="collapseStatistics">
                        <span class="glyphicon glyphicon-stats dnl-link-icon"></span>
                        <span class="dnl-link-text">Statistics</span>
                        <span class="fa fa-angle-up dnl-btn-sub-collapse"></span>
                    </a>
                    <!-- Dropdown level one -->
                    <ul class="dnl-sub-one collapse" id="collapseStatistics">
                        <li>
                            <a href="#">
                                <span class="fa fa-clock-o dnl-link-icon"></span>
                                <span class="dnl-link-text">Daily</span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="fa fa-history dnl-link-icon"></span>
                                <span class="dnl-link-text">Annual</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#">
                        <span class="glyphicon glyphicon-folder-open dnl-link-icon"></span>
                        <span class="dnl-link-text">Pages</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="glyphicon glyphicon-comment dnl-link-icon"></span>
                        <span class="dnl-link-text">Comments</span>
                        <span class="badge">4</span>
                    </a>
                </li>
                <li>
                    <a class="collapsed" data-toggle="collapse" href="#collapseLevelOne" aria-expanded="false" aria-controls="collapseLevelOne">
                        <span class="fa fa-sort-amount-desc dnl-link-icon"></span>
                        <span class="dnl-link-text">Dropdown level 1</span>
                        <span class="fa fa-angle-up dnl-btn-sub-collapse"></span>
                    </a>
                    <!-- Dropdown level one -->
                    <ul class="dnl-sub-one collapse" id="collapseLevelOne">
                        <li>
                            <a href="#">
                                <span class="fa fa-slack dnl-link-icon"></span>
                                <span class="dnl-link-text">Level 1</span>
                            </a>
                        </li>
                        <li>
                            <a class="collapsed" data-toggle="collapse" href="#collapseLevelTwo" aria-expanded="false" aria-controls="collapseLevelTwo">
                                <span class="fa fa-level-down dnl-link-icon"></span>
                                <span class="dnl-link-text">Dropdown level 2</span>
                                <span class="fa fa-angle-up dnl-btn-sub-collapse"></span>
                            </a>
                            <!-- Dropdown level two -->
                            <ul class="dnl-sub-two collapse" id="collapseLevelTwo">
                                <li>
                                    <a href="#">
                                        <span class="fa fa-wifi dnl-link-icon"></span>
                                        <span class="dnl-link-text">Level 2</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <span class="fa fa-wifi dnl-link-icon"></span>
                                        <span class="dnl-link-text">Level 2</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <span class="fa fa-wifi dnl-link-icon"></span>
                                        <span class="dnl-link-text">Level 2</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#">
                                <span class="fa fa-slack dnl-link-icon"></span>
                                <span class="dnl-link-text">Level 1</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
            <p class="dnl-nav-title">Filter</p>
            <ul class="dnl-nav">
                <li>
                    <a href="#">
                        <span class="fa fa-image dnl-link-icon"></span>
                        <span class="dnl-link-text">Image</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="fa fa-video-camera dnl-link-icon"></span>
                        <span class="dnl-link-text">Video</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="glyphicon glyphicon-folder-open dnl-link-icon"></span>
                        <span class="dnl-link-text">Audio</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="fa fa-file-text dnl-link-icon"></span>
                        <span class="dnl-link-text">File</span>
                        <span class="badge">4</span>
                    </a>
                </li>
                <li class="active">
                    <a href="#">
                        <span class="fa fa-link dnl-link-icon"></span>
                        <span class="dnl-link-text">Active link</span>
                    </a>
                </li>
            </ul>
            <p class="dnl-nav-title">Category</p>
            <ul class="dnl-nav">
                <li>
                    <a class="collapsed" data-toggle="collapse" href="#collapseCategoryAll" aria-expanded="false" aria-controls="collapseCategoryAll">
                        <span class="glyphicon glyphicon-tags dnl-link-icon"></span>
                        <span class="dnl-link-text">All</span>
                        <span class="fa fa-angle-up dnl-btn-sub-collapse"></span>
                    </a>
                    <!-- Dropdown level one -->
                    <ul class="dnl-sub-one collapse" id="collapseCategoryAll">
                        <li>
                            <a href="#">
                                <span class="fa fa-dot-circle-o dnl-link-icon"></span>
                                <span class="dnl-link-text">UI</span>
                                <span class="badge">4</span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="fa fa-dot-circle-o dnl-link-icon"></span>
                                <span class="dnl-link-text">Design</span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="fa fa-dot-circle-o dnl-link-icon"></span>
                                <span class="dnl-link-text">App</span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="fa fa-dot-circle-o dnl-link-icon"></span>
                                <span class="dnl-link-text">Homepage</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#">
                        <span class="fa fa-dot-circle-o dnl-link-icon"></span>
                        <span class="dnl-link-text">Popular</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="fa fa-dot-circle-o dnl-link-icon"></span>
                        <span class="dnl-link-text">Handpicked</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- /.dash-navbar-left -->

        <div class="content-wrap dnl-hidden" data-effect="dnl-opacity">
            <div class="container-fluid" compile="RightContent">
            </div>
        </div>
        <!-- /.content-wrap -->
    </div>
    <!-- /.main-wrap -->

</body>
</html>
