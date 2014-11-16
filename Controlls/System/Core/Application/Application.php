<?php

require_once("./Controlls/Shared/Definitions.php");
require_once("./Controlls/Shared/Common.php");

class Application {
    static $Config = array();
    static $Class = array();
    static $JSON = array();
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
        #region - Fetching Module & Function
        $sModulePath = $this->Input->get('Module');
        $sModulePath = ucfirst(strtolower($sModulePath !== false ? $sModulePath : DEFAULT_CONTROLLER));
        $arModule = explode('/', $sModulePath);
        $sModule = ucfirst(end($arModule));
        $sFunction = $this->Input->get('Function');
        $sFunction = ucfirst(strtolower($sFunction !== false ? $sFunction : DEFAULT_FUNCTION));
        #endregion
        
        #region - Load Template - 
        $sTemplate =  self::GetConfig('template') !== false ? self::GetConfig('template') : DEFAULT_TEMPLATE;
        $sTemplateDir =  '../Templates/'.$sTemplate;
        $TemplateInfo = self::LoadJSON($sTemplate, TEMPLATES);
        #endregion
        
        #region - Load Module -
        $Module = self::LoadModule($sModulePath);
        if(!method_exists($Module, $sFunction)) {
            show_error("Function '".$sFunction."' doesn't exists in class '".$sModulePath."'.");
        }
        $ModuleInfo = self::LoadJSON($sModulePath, MODULES, $Module);
        #endregion
        
        #region - Parsing Data to Template -
        $arData = array();
        $arData['RightContent'] = call_user_func_array(array(&$Module, $sFunction), array());
        $arData['PreloadedJS'] = $this->PreloadedJSs;
        $arData['PreloadedCSS'] = $this->PreloadedCSSs;
        echo $this->Parser->Parse('Main', $sTemplateDir, $arData);
        #endregion
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
    
    public static function LoadJSON($sNamePath, $sType = MODULES, &$Module = null) {
        $arName = explode('/', $sNamePath);
        $sName = ucfirst(end($arName));
        if(isset(self::$JSON[$sName])) {
            return self::$JSON[$sName];
        }
        if($sType == MODULES) {
            $sDir = APPPATH.MODULES.DIRECTORY_SEPARATOR.$sNamePath.DIRECTORY_SEPARATOR;
            $sPath = $sDir.MODULE_JSON;
            $sString = 'module';
        } elseif($sType == TEMPLATES) {
            $sDir = APPPATH.TEMPLATES.DIRECTORY_SEPARATOR.$sNamePath.DIRECTORY_SEPARATOR;
            $sPath = $sDir.TEMPLATE_JSON;
            $sString = 'template';
        } else {
            show_error("Invalid request for JSON.");
        }
        $Object = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', self::LoadFile($sPath)));
        if(!$Object->Enabled) {
            show_error("The ".$sString." '".$sName."' is not enabled.");
        }
        if(property_exists($Object, 'AngularJSIncluded') && $Object->AngularJSIncluded) {
            self::$_this->LoadJS(ACPATH.$sDir.JS.DIRECTORY_SEPARATOR.'Angular'.$sName.'.'.JS);
        }
        foreach($Object->CSS as $sLink) {
            self::$_this->LoadCSS(ACPATH.$sDir.$sLink);
        }
        foreach($Object->JS as $sLink) {
            self::$_this->LoadJS(ACPATH.$sDir.$sLink);
        }
        $Object->Dir = $sDir;
        if(!is_null($Module)) {
            foreach (get_object_vars($Object) as $sKey => $vValue) {
                $Module->$sKey = $vValue;
            }
        }
        return $Object; 
    }
    
    public static function LoadTemplate($sName, $sModule) {
        $sFile = APPPATH.MODULES.DIRECTORY_SEPARATOR.$sModule.DIRECTORY_SEPARATOR.VIEWS.DIRECTORY_SEPARATOR.$sName.EXT;
        
        return self::LoadFile($sFile);
    }
    
    public static function LoadFile($sFile, $bRequre = false) {
        if(!file_exists($sFile)) {
            show_error("Unable to load file '".$sFile."'.");
        }
        
        if($bRequre) {
            require_once($sFile);
        } else {
            return file_get_contents($sFile);
        }
    }
    
    public static function Load($sNamePath, $sType = LIBRARIES, $bInitializeClass = true) {
        $arName = explode('/', $sNamePath);
        $sName = ucfirst(end($arName));
        if($sType == LIBRARIES && isset(self::$Class[$sName])) {
            return self::$Class[$sName];
        }
        if($sType == MODULES) {
            $sFile = APPPATH.$sType.DIRECTORY_SEPARATOR.$sNamePath.DIRECTORY_SEPARATOR.$sName.EXT;
        } else {
            $sFile = SYSDIR.$sType.DIRECTORY_SEPARATOR.$sNamePath.EXT;
        }
        
        self::LoadFile($sFile, true);
        $sClassName = $sType == LIBRARIES ? AC.$sName : $sName;
        if(in_array($sType, array(LIBRARIES, MODULES))) {
            if(!class_exists($sClassName)) {
                show_error("Unable to load class '".$sName."' from file '".$sFile."'.");
            }
            if($bInitializeClass) {
                $Class = new $sClassName();
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