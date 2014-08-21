<?php

$sApplicationFiles = 'ApplicationFiles';

$sSystemPath = 'Controlls/System';

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


// The path to the "application" folder
if (is_dir($sApplicationFiles)) {
    define('APPPATH', $sApplicationFiles.'/');
} else {
    exit("Your application folder path does not appear to be set correctly. Please open the following file and correct this: ".SELF);
}