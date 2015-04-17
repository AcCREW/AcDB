<?php

require_once("./Controlls/Shared/Definitions.php");
require_once("./Controlls/Shared/Common.php");
require_once("./Controlls/System/Libraries/Loader.php");

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
    const _ACTION_SAVE = 1001;
    
    static $_this = null;
    static $Title = null;
    
    static $DumpContent = '';
    
    static $Config = array();
    
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
        if(!isset(Loader::$Class[$sName])) {
            if(($Error = Loader::LoadLibrary($sName)) instanceof Error) {
                show_error($Error->Message);
            }
        }
        
        return Loader::$Class[$sName];
    }

    public function Start() {
        switch ($this->Action) {
            case self::_ACTION_SAVE:
                $ApplicationGenerator = new ApplicationGenerator();
                $ApplicationGenerator->Generate();
                break;
            case self::_ACTION_INITIALIZE_ACDB:
                #region - Load Template - 
                $sTemplate =  self::GetConfig('template') !== false ? self::GetConfig('template') : DEFAULT_TEMPLATE;
                $sTemplateDir =  '../Templates/'.$sTemplate;
                Loader::LoadJSON($sTemplate, TEMPLATES);
                
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
                
                if(($Error = Loader::LoadModule($sModule)) instanceof Error) {
                    show_error($Error->Message);
                }
                $Module = new $sModule();
                if(!method_exists($Module, $sFunction)) {
                    show_error("Function '".$sFunction."' doesn't exists in class '".$sModule."'.");
                }
                Loader::LoadJSON($sModule, MODULES, $Module);
                $sContent = call_user_func_array(array(&$Module, $sFunction), array());
                
                $arData = array();
                //Dump($this->Check->CompareTimes(APP_START));
                //Dump($this->Check->CompareMemories());
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

        $arNotNeeded = array('Check', 'Utf8', 'AcObject');
        foreach($arNotNeeded as $sNotNeeded) {
            $vCheckIfExists = array_search($sNotNeeded, $arAutoloadLibraries);
            if($vCheckIfExists !== false) {
                unset($arAutoloadLibraries[$vCheckIfExists]);
            }
            Loader::LoadLibrary($sNotNeeded, $sNotNeeded != 'AcObject');
        }
        foreach($arAutoloadLibraries as $sLybraryName) {
            Loader::LoadLibrary($sLybraryName);
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
    if(($Error = Application::LoadLibrary($sClass, $sClass == 'DB')) instanceof Error) {
        if(($Error = Application::LoadModule($sClass, false)) instanceof Error) {
        }
    }
}
spl_autoload_register('AcAutoLoader');
