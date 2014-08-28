<?php

require_once("./Controlls/Shared/Definitions.php");
require_once("./Controlls/Shared/Common.php");

class Application {
    static $Config = array();
    static $Class = array();
    static $_this = null;

    public function Application() {
        self::$_this = &$this;
        $this->Initialize();
    }

    public function &__get($sName) {
        if(isset(self::$Class[$sName])) {
            return self::$Class[$sName];
        }

        return self::LoadLibrary($sName);
    }

    public function Start() {
        $arSegments = $this->URI->segments;
        $sModule = ucfirst(strtolower(isset($arSegments[0]) ? $arSegments[0] : DEFAULT_CONTROLLER));
        $sFunction = ucfirst(strtolower(isset($arSegments[1]) ? $arSegments[1] : $sModule));
        
        $Module = self::LoadModule($sModule);
        if(!method_exists($Module, $sFunction)) {
            exit("Function '".$sFunction."' doesn't exists in class '".$sModule."'.");
        }
        $sContent = call_user_func_array(array(&$Module, $sFunction), array_slice($arSegments, 2));
        
        echo $sContent;
    }

    private function Initialize() {
        $arAutoloadLibraries = array_unique(self::GetConfig('autoload_libraries'));

        $arNotNeeded = array('Check', 'Utf8', 'Controller');
        foreach($arNotNeeded as $sNotNeeded) {
            $vCheckIfExists = array_search($sNotNeeded, $arAutoloadLibraries);
            if($vCheckIfExists !== false) {
                unset($arAutoloadLibraries[$vCheckIfExists]);
            }
            self::LoadLibrary($sNotNeeded, $sNotNeeded != 'Controller');
        }
        foreach($arAutoloadLibraries as $sLybraryName) {
            self::LoadLibrary($sLybraryName);
        }
        foreach(self::GetConfig('autoload_helpers') as $sHelperName) {
            self::LoadHelper($sHelperName);
        }
    }

    public static function LoadLibrary($sName, $bInitializeClass = true) {
        return self::Load($sName, 'Libraries', $bInitializeClass);
    }
    
    public static function LoadModule($sName) {
        return self::Load($sName, 'Modules');
    }

    public static function LoadHelper($sName) {
        return self::Load($sName, 'Helpers');
    }

    public static function Load($sName, $sType = 'Libraries', $bInitializeClass = true) {
        if($sType == 'Libraries' && isset(self::$Class[$sName])) {
            return self::$Class[$sName];
        }
        if($sType == 'Modules') {
            $sFile = APPPATH.$sType.DIRECTORY_SEPARATOR.$sName.DIRECTORY_SEPARATOR.$sName.EXT;
        } else {
            $sFile = SYSDIR.$sType.DIRECTORY_SEPARATOR.$sName.EXT;
        }
        if(!is_file($sFile)) {
            exit("Unable to load file '".$sFile."'.");
        }
        require_once($sFile);
        if(in_array($sType, array('Libraries', 'Modules'))) {
            if(!class_exists($sName)) {
                exit("Unable to load class '".$sName."' from file '".$sFile."'.");
            }
            if($bInitializeClass) {
                $Class = new $sName;
                self::$Class[$sName] = $Class;
                return $Class;
            } else {
                return true;
            }
        }
    }

    public static function SetConfig($sKey, $vValue) {
        self::$Config[$sKey] = $vValue;
    }

    public static function GetConfig($sKey, $bStrict = false) {
        if(!isset(self::$Config[$sKey])) {
            if($bStrict) {
                show_error("Can't find config with key '".$sKey."'.");
            }
            return false;
        }
        return self::$Config[$sKey];
    }
}

require_once(APPPATH."Config/Config.php");