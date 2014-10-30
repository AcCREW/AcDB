<?php

date_default_timezone_set('Europe/Sofia');

$sApplicationFiles = 'ApplicationFiles';

$sSystemPath = 'Controlls/System';

define('DEFAULT_CONTROLLER', 'Index');
define('DEFAULT_TEMPLATE', 'Default');
define('TEMPLATE_JSON', 'template.json');
define('MODULE_JSON', 'module.json');

define('ACPATH', 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);

// Set the current directory correctly for CLI requests
if (defined('STDIN')) {
    chdir(dirname(__FILE__));
}

// Is the system path correct?
if (is_dir($sSystemPath)){ 
    define('SYSPATH', $sSystemPath.'/');
    
    if (realpath($sSystemPath) !== FALSE) {
        $sSystemPath = realpath($sSystemPath).'/';
    }

    // ensure there's a trailing slash
    $sSystemPath = rtrim($sSystemPath, '/').'/';
    
    // Path to the system folder
    define('SYSDIR', str_replace("\\", "/", $sSystemPath));
} else { 
    exit("Your system folder path does not appear to be set correctly. Please open the following file and correct this: ".pathinfo(__FILE__, PATHINFO_BASENAME));
}

// The PHP file extension
// this global constant is deprecated.
define('EXT', '.php');
define('AC', 'Ac');
define('APP_START', 'APP_START');
define('MODULES', 'Modules');
define('VIEWS', 'Views');
define('LIBRARIES', 'Libraries');
define('HELPERS', 'Helpers');
define('TEMPLATES', 'Templates');


// The path to the "application" folder
if (is_dir($sApplicationFiles)) {
    define('APPPATH', $sApplicationFiles.'/');
} else {
    exit("Your application folder path does not appear to be set correctly. Please open the following file and correct this: ".SELF);
}

