<?php

require_once("./Controlls/Shared/Definitions.php");
require_once("./Controlls/Shared/Common.php");

class Application {
    static $Config = array();
    static $Class = array();
    static $_this = null;

    public function Application() {
        self::$_this = $this;
        $this->Initialize();
    }

    public function __get($sName) {
        if(isset(self::$Class[$sName])) {
            return self::$Class[$sName];
        }

        return self::LoadLibrary($sName);
    }

    public function Start() {
        $this->Session->set_userdata('user_id', 1993);
    }

    private function Initialize() {
        foreach(self::GetConfig('autoload_libraries') as $sLybraryName) {
            self::LoadLibrary($sLybraryName);
        }
        foreach(self::GetConfig('autoload_helpers') as $sHelperName) {
            self::LoadHelper($sHelperName);
        }
    }

    public static function LoadLibrary($sName) {
        return self::Load($sName, 'Libraries');
    }

    public static function LoadHelper($sName) {
        return self::Load($sName, 'Helpers');
    }

    public static function Load($sName, $sType = 'Libraries') {
        if($sType == 'Libraries' && isset(self::$Class[$sName])) {
            return self::$Class[$sName];
        }
        $sFile = SYSDIR.$sType.DIRECTORY_SEPARATOR.$sName.EXT;
        if(!is_file($sFile)) {
            exit("Unable to load file '".$sFile."'.");
        }
        require_once($sFile);
        if($sType == 'Libraries') {
            if(!class_exists($sName)) {
                exit("Unable to load class '".$sName."' from file '".$sFile."'.");
            }
            $Class = new $sName;
            self::$Class[$sName] = $Class;
            return $Class;
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

require_once("./Controlls/Shared/Config.php");