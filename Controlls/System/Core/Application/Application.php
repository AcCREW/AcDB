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
        return self::Load($sName, LIBRARIES, $bInitializeClass);
    }
    
    public static function LoadModule($sName) {
        return self::Load($sName, MODULES);
    }

    public static function LoadHelper($sName) {
        return self::Load($sName, HELPERS);
    }
    
    public static function LoadTemplate($sName, $sModule) {
        $sFile = APPPATH.MODULES.DIRECTORY_SEPARATOR.$sModule.DIRECTORY_SEPARATOR.VIEWS.DIRECTORY_SEPARATOR.$sName.EXT;
        if(!file_exists($sFile)) {
            exit("Unable to load file '".$sFile."'.");
        }
        
        return file_get_contents($sFile);
    }

    public static function Load($sName, $sType = LIBRARIES, $bInitializeClass = true) {
        if($sType == LIBRARIES && isset(self::$Class[$sName])) {
            return self::$Class[$sName];
        }
        if($sType == MODULES) {
            $sFile = APPPATH.$sType.DIRECTORY_SEPARATOR.$sName.DIRECTORY_SEPARATOR.$sName.EXT;
        } else {
            $sFile = SYSDIR.$sType.DIRECTORY_SEPARATOR.$sName.EXT;
        }
        if(!is_file($sFile)) {
            exit("Unable to load file '".$sFile."'.");
        }
        require_once($sFile);
        $sClassName = $sType == LIBRARIES ? AC.$sName : $sName;
        if(in_array($sType, array(LIBRARIES, MODULES))) {
            if(!class_exists($sClassName)) {
                exit("Unable to load class '".$sName."' from file '".$sFile."'.");
            }
            if($bInitializeClass) {
                $Class = new $sClassName;
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