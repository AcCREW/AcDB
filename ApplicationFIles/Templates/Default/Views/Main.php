<!DOCTYPE html>
<html lang="en" ng-app="AcGenerator">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>AcGenerator</title>
    {PreloadedCSS}
    <link rel="stylesheet" href="{Link}" />
    {/PreloadedCSS}
    {PreloadedJS}
    <script src="{Link}"></script>
    {/PreloadedJS}
    <style>
        #user_settings::after {
            content: none !important;
        }

        #user_settings {
            padding-right: 0 !important;
            margin-right: 0 !important;
        }
    </style>

</head>
<body class="metro">
    <div class="navigation-bar dark">
        <div class="navigation-bar-content">
            <a class="element">
                <i class="fa fa-gears"></i>
                AcGenerator
                <small>[stop coding bulshitz]</small>
            </a>
            <span class="element-divider"></span>
            <a class="element1 pull-menu" href="#"></a>
            <ul class="element-menu">
                <li>
                    <a href="{BaseURL}">
                        <i class="fa fa-home"></i>
                        Home
                    </a>
                </li>
                <span class="element-divider"></span>
                <li>
                    <a href="{BaseURL}index.php?Module=Acp/Object">
                        <i class="fa fa-plus"></i>
                        New object
                    </a>
                </li>
            </ul>
            <span class="element-divider"></span>
        </div>
    </div>
    <div class="grid fluid">
        <div class="row" style="margin-top: 0;">
            <div class="span3 no-tablet">
                <nav class="sidebar light">
                    <ul>
                        <li style="margin-top: 0;" class="active">
                            <a href="#">
                                <i class="fa fa-home"></i>
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="fa fa-cog"></i>
                                Layouts
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-toggle" href="#">
                                <i class="fa fa-plus-square"></i>
                                Sub menu
                            </a>
                            <ul class="dropdown-menu" data-role="dropdown">
                                <li>
                                    <a href="">Subitem 1</a>
                                </li>
                                <li>
                                    <a href="">Subitem 2</a>
                                </li>
                                <li>
                                    <a href="">Subitem 3</a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="">Subitem 4</a>
                                </li>
                                <li class="disabled">
                                    <a href="">Subitem 4</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#">Thread item</a>
                        </li>
                        <li class="disabled">
                            <a href="#">Disabled item</a>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="span9 left_content_span9" style="border: 1px solid #eeeeee; color: #555555;">
                <div class="right_content">
                    {RightContent}
                </div>
            </div>
        </div>
    </div>
</body>
</html>
