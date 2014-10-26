<?php

require_once("./Controlls/Shared/Definitions.php");
require_once("./Controlls/Shared/Common.php");

class Application {
    static $Config = array();
    static $Class = array();
    static $_this = null;
    
    private $PreloadedJSs = array();
    private $PreloadedCSSs = array();

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
        
        $sTemplate =  self::GetConfig('template') !== false ? self::GetConfig('template') : DEFAULT_TEMPLATE;
        $sTemplateDir =  'Templates/'.$sTemplate;
        $sTMPDir = APPPATH.MODULES.DIRECTORY_SEPARATOR.$sTemplateDir.DIRECTORY_SEPARATOR;

        $arTemplateJson = json_decode(self::LoadJSON($sTMPDir.'template.json'));
        if(!$arTemplateJson->Enabled) {
            exit("The template '".$sTemplate."' is not enabled.");
        }
        foreach($arTemplateJson->CSS as $sLink) {
            $this->LoadCSS($sTMPDir.$sLink);
        }
        foreach($arTemplateJson->JS as $sLink) {
            $this->LoadJS($sTMPDir.$sLink);
        }
        $arData = array();
        $arData['Content'] = call_user_func_array(array(&$Module, $sFunction), array_slice($arSegments, 2));
        $arData['JS'] = $this->PreloadedJSs;
        $arData['CSS'] = $this->PreloadedCSSs;
        
        echo $this->Parser->Parse('Main', $sTemplateDir, $arData);
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
    
    public static function LoadJSON($sName) {
        return preg_replace('/[\x00-\x1F\x80-\xFF]/', '', self::LoadFile($sName)); 
    }
    
    public static function LoadTemplate($sName, $sModule) {
        $sFile = APPPATH.MODULES.DIRECTORY_SEPARATOR.$sModule.DIRECTORY_SEPARATOR.VIEWS.DIRECTORY_SEPARATOR.$sName.EXT;
        
        return self::LoadFile($sFile);
    }
    
    public static function LoadFile($sFile, $bRequre = false) {
        if(!file_exists($sFile)) {
            exit("Unable to load file '".$sFile."'.");
        }
        
        if($bRequre) {
            require_once($sFile);
        } else {
            return file_get_contents($sFile);
        }
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
        
        self::LoadFile($sFile, true);
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
    
    public function LoadJS($vLink = null) {
        if(empty($vLink)) {
            return false;
        }
        
        if(!is_array($vLink)) {
            $vLink = array('Link' => $vLink);
        }
        
        $this->PreloadedJSs = array_merge($this->PreloadedJSs, array($vLink));
    }
    
    public function LoadCSS($vLink = null) {
        if(empty($vLink)) {
            return false;
        }
        
        if(!is_array($vLink)) {
            $vLink = array('Link' => $vLink);
        }
        
        $this->PreloadedCSSs = array_merge($this->PreloadedCSSs, array($vLink));
    }
}

require_once(APPPATH."Config/Config.php");