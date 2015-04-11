<?php

require_once("./Controlls/Shared/Definitions.php");
require_once("./Controlls/Shared/Common.php");
/**
 * @property Application $_this instance
 * @property string $Title Module title
 * @property array $Class Stores all loaded classes
 * @property array $JSON Stores all loaded JSONs
 * @property array $Config Stores all configs
 * @property string $DumpContent Stores all dumps and print them in content
 * @property DB $DB DB Class
 * @property Encrypt $Encrypt Encrypt Class
 * @property Check $Check Check Class
 * @property Input $Input Input Class
 * @property Parser $Parser Parser Class
 * @property Security $Security Security Class
 * @property Session $Session Session Class
 * @property SHA1 $SHA1 SHA1 Class
 * @property URI $URI URI Class
 * @property Utf8 $Utf8 Utf8 Class
 */
class Application {
    const _ACTION_INITIALIZE_ACDB = 0;
    const _ACTION_LOAD_MODULE = 1000;
    const _ACTION_GENERATE = 1001;
    
    static $Config = array();
    static $Class = array();
    static $JSON = array();
    static $_this = null;
    static $Title = null;
    static $DumpContent = '';
    
    protected $Action = self::_ACTION_INITIALIZE_ACDB;
    
    private $PreloadedJSs = null;
    private $PreloadedJSSchemes = null;
    private $PreloadedCSSs = array();

    public function Application() {
        self::$_this = &$this;
        $this->Initialize();
        $this->InitializeAction();
    }

    public function &__get($sName) {
        if(!isset(self::$Class[$sName])) {
            if(($Error = self::LoadLibrary($sName)) instanceof Error) {
                show_error($Error->Message);
            }
        }
        
        return self::$Class[$sName];
    }

    public function Start() {
        
        switch ($this->Action) {
            case self::_ACTION_GENERATE:
                $ApplicationGenerator = new ApplicationGenerator();
                $ApplicationGenerator->Generate();
                break;
            case self::_ACTION_INITIALIZE_ACDB:
                #region - Load Template - 
                $sTemplate =  self::GetConfig('template') !== false ? self::GetConfig('template') : DEFAULT_TEMPLATE;
                $sTemplateDir =  '../Templates/'.$sTemplate;
                self::LoadJSON($sTemplate, TEMPLATES);
                
                $arData = array();
                $arData['ModuleTitle'] = Application::$Title;
                $arData['SiteTitle'] = Application::GetConfig('site_title');
                $arData['RightContent'] = '';
                $arData['PreloadedJS'] = json_encode($this->PreloadedJSs);
                $arData['PreloadedJSScheme'] = json_encode($this->PreloadedJSSchemes);
                $arData['PreloadedCSS'] = $this->PreloadedCSSs;
                echo $this->Parser->Parse('Main', $sTemplateDir, $arData);
                #endregion
                break;
            case self::_ACTION_LOAD_MODULE:
                #region - Load Module - 
                $sModule = $this->Input->get('Module');
                $sModule = $sModule !== false && !empty($sModule) ? $sModule : DEFAULT_CONTROLLER;
                $sFunction = $this->Input->get('Function');
                $sFunction = $sFunction !== false && !empty($sFunction) ? $sFunction : DEFAULT_FUNCTION;
                
                if(($Error = self::LoadModule($sModule)) instanceof Error) {
                    show_error($Error->Message);
                }
                $Module = new $sModule();
                if(!method_exists($Module, $sFunction)) {
                    show_error("Function '".$sFunction."' doesn't exists in class '".$sModule."'.");
                }
                self::LoadJSON($sModule, MODULES, $Module);
                $sContent = call_user_func_array(array(&$Module, $sFunction), array());
                
                $arData = array();
                $arData['ModuleTitle'] = Application::$Title;
                $arData['SiteTitle'] = Application::GetConfig('site_title');
                $arData['Content'] = self::$DumpContent.$sContent;
                header('Content-Type: application/json');
                exit (json_encode($arData));
                #endregion
        }
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
            $this->Action = $nAction;    
        }
    }

    public static function LoadLibrary($sName, $bInitializeClass = true) {
        return self::Load($sName, LIBRARIES, $bInitializeClass);
    }
    
    public static function LoadModule($sName, $bInitializeClass = true) {
        return self::Load($sName, MODULES, $bInitializeClass);
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
        $sPath = null;
        $sString = null;
        $sDir = null;
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
            return new Error("Unable to load file '".$sFile."'.");
        }
        
        if($bRequre) {
            require_once($sFile);
            return true;
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
        
        if(($Error = self::LoadFile($sFile, true)) instanceof Error) {
            return $Error;
        }
        if($sType == LIBRARIES) {
            if(!class_exists($sName)) {
                return new Error("Unable to load class '".$sName."' from file '".$sFile."'.");
            }
            if($bInitializeClass) {
                $Class = new $sName();
                self::$Class[$sName] = $Class;
                return $Class;
            } else {
                return true;
            }
        }
        
        return true;
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
        return true;
    }
    
    public function LoadJSScheme($sKey, $sLink) {
        if(empty($sKey)) {
            return false;
        }
        
        $this->PreloadedJSSchemes->$sKey = $sLink;
        return true;
    }
    
    public function LoadCSS($vLink = null) {
        if(empty($vLink)) {
            return false;
        }
        
        if(!is_array($vLink)) {
            $vLink = array('Link' => $vLink);
        }
        
        $this->PreloadedCSSs = array_merge($this->PreloadedCSSs, array($vLink));
        return true;
    }
}

require_once(APPPATH."Config/Config.php");

function AcAutoLoader($sClass) {
    Dump($sClass);
    if(($Error = Application::LoadLibrary($sClass, $sClass == 'DB')) instanceof Error) {
        if(($Error = Application::LoadModule($sClass, false)) instanceof Error) {
        }
    }
}
spl_autoload_register('AcAutoLoader');
