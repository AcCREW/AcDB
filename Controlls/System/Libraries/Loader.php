<?php

class Loader {
    static $JSON = array();
    
    public static function LoadLibrary($sName, $bInitialize) {
        return self::Load($sName, LIBRARIES, $bInitialize);
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
            Application::$_this->LoadJS('Angular'.$sName, ACPATH.$sDir.JS.'/'.'Angular'.$sName);
            Application::$_this->LoadJSScheme('Angular'.$sName, array());
        }
        if(property_exists($Object, 'CSS')) {
            foreach($Object->CSS as $sLink) {
                Application::$_this->LoadCSS(ACPATH.$sDir.$sLink);
            }
        }
        if(property_exists($Object, 'JS')) {
            foreach($Object->JS as $sKey => $sLink) {
                Application::$_this->LoadJS($sKey, ACPATH.$sDir.$sLink);
            }
        }
        if(property_exists($Object, 'JSSchemes')) {
            foreach($Object->JSSchemes as $sKey => $sLink) {
                Application::$_this->LoadJSScheme($sKey, $sLink);
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
    
    public static function Load($sName, $sType = LIBRARIES, $bInitialize = false) {
        $sTMPLoadName = $sName;
        if(substr($sTMPLoadName, 0, 1) == 'C') {
            $sTMPLoadName = substr($sTMPLoadName, 1);
        }
        
        if($sType == MODULES) {
            $sFile = APPPATH.$sType.'/'.$sTMPLoadName.'/'.$sTMPLoadName.EXT;
        } else {
            $sFile = SYSDIR.$sType.'/'.$sTMPLoadName.EXT;
        }
        
        if(($Error = self::LoadFile($sFile, true)) instanceof Error) {
            return $Error;
        }
        
        if($sType == LIBRARIES && $bInitialize && method_exists($sName, 'Initialize')) {
            $sName::Initialize();
        }
        
        return true;
    }
}