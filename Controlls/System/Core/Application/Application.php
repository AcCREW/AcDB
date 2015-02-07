<?php

require_once("./Controlls/Shared/Definitions.php");
require_once("./Controlls/Shared/Common.php");

class Application {
    static $Config = array();
    static $Class = array();
    static $JSON = array();
    static $_this = null;
    static $Title = null;
    
    private $PreloadedJSs = null;
    private $PreloadedJSSchemes = null;
    private $PreloadedCSSs = array();
    
    protected $IsJSON = false;

    public function Application() {
        self::$_this = &$this;
        $this->Initialize();
        $this->InitializeAction();
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
        $sModulePath = $sModulePath !== false ? $sModulePath : DEFAULT_CONTROLLER;
        $arModule = explode('/', $sModulePath);
        $sModule = ucfirst(end($arModule));
        $sFunction = $this->Input->get('Function');
        $sFunction = $sFunction !== false ? $sFunction : DEFAULT_FUNCTION;
        #endregion
        
        #region - Load Template - 
        if(!$this->IsJSON) {
            $sTemplate =  self::GetConfig('template') !== false ? self::GetConfig('template') : DEFAULT_TEMPLATE;
            $sTemplateDir =  '../Templates/'.$sTemplate;
            $TemplateInfo = self::LoadJSON($sTemplate, TEMPLATES);
        }
        #endregion
        
        #region - Load Module -
        $sContent = '';
        if($this->IsJSON) {
            $Module = self::LoadModule($sModulePath);
            if(!method_exists($Module, $sFunction)) {
                show_error("Function '".$sFunction."' doesn't exists in class '".$sModulePath."'.");
            }
            $ModuleInfo = self::LoadJSON($sModulePath, MODULES, $Module);
            $sContent = call_user_func_array(array(&$Module, $sFunction), array());
        }
        #endregion
        
        #region - Parsing Data to Template -
        $arData = array();
        $arData['ModuleTitle'] = Application::$Title;
        $arData['SiteTitle'] = Application::GetConfig('site_title');
        if($this->IsJSON) {
            $arData['Content'] = $sContent;
            header('Content-Type: application/json');
            echo json_encode($arData);
        } else {
            $arData['RightContent'] = $sContent;
            $arData['PreloadedJS'] = json_encode($this->PreloadedJSs);
            $arData['PreloadedJSScheme'] = json_encode($this->PreloadedJSSchemes);
            $arData['PreloadedCSS'] = $this->PreloadedCSSs;
            echo $this->Parser->Parse('Main', $sTemplateDir, $arData);
        }
        #endregion
    }

    private function Initialize() {
        $this->PreloadedJSs = new stdClass();
        $this->PreloadedJSSchemes = new stdClass();
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
    
    private function InitializeAction() {
        $nAction = $this->Input->get('Action');
        if($nAction !== false) {
            if($nAction == ACTION_AJAX_LOAD) {
                $this->IsJSON = true;
            }
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
            $sDir = APPPATH.MODULES.'/'.$sNamePath.'/';
            $sPath = $sDir.MODULE_JSON;
            $sString = 'module';
        } elseif($sType == TEMPLATES) {
            $sDir = APPPATH.TEMPLATES.'/'.$sNamePath.'/';
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
            self::$_this->LoadJS('Angular'.$sName, ACPATH.$sDir.JS.'/'.'Angular'.$sName);
            self::$_this->LoadJSScheme('Angular'.$sName, array());
        }
        if(property_exists($Object, 'CSS')) {
            foreach($Object->CSS as $sLink) {
                self::$_this->LoadCSS(ACPATH.$sDir.$sLink);
            }
        }
        if(property_exists($Object, 'JS')) {
            foreach($Object->JS as $sKey => $sLink) {
                self::$_this->LoadJS($sKey, ACPATH.$sDir.$sLink);
            }
        }
        if(property_exists($Object, 'JSSchemes')) {
            foreach($Object->JSSchemes as $sKey => $sLink) {
                self::$_this->LoadJSScheme($sKey, $sLink);
            }
        }
        $Object->Dir = $sDir;
        if(!is_null($Module)) {
            foreach (get_object_vars($Object) as $sKey => $vValue) {
                $Module->$sKey = $vValue;
            }
            if(property_exists($Object, 'Module')) {
                Application::$Title = $Object->Module;
            }
        }
        return $Object; 
    }
    
    public static function LoadTemplate($sName, $sModule) {
        $sFile = APPPATH.MODULES.'/'.$sModule.'/'.VIEWS.'/'.$sName.EXT;
        
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
        $sName = end($arName);
        if($sType == LIBRARIES && isset(self::$Class[$sName])) {
            return self::$Class[$sName];
        }
        if($sType == MODULES) {
            $sFile = APPPATH.$sType.'/'.$sNamePath.'/'.$sName.EXT;
        } else {
            $sFile = SYSDIR.$sType.'/'.$sNamePath.EXT;
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
    
    public function LoadJS($sKey, $sLink) {
        if(empty($sLink) || empty($sKey)) {
            return false;
        }
        
        $this->PreloadedJSs->$sKey = $sLink;
    }
    
    public function LoadJSScheme($sKey, $sLink) {
        if(empty($sKey)) {
            return false;
        }
        
        $this->PreloadedJSSchemes->$sKey = $sLink;
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