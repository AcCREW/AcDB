<?php

require_once("./Controlls/Shared/Definitions.php");
require_once("./Controlls/Shared/Common.php");

class Application
{
    static $URI = null;
        
    public function Application() {
        $this->InitializeClasses();
    }
    
    private function InitializeClasses() {
        self::$URI = $this->LoadSystemClass('URI');
        var_dump(self::$URI->segments);
    }
    
    public function LoadSystemClass($sName) {
        $sFile = SYSDIR.$sName.'/'.$sName.EXT;
        if(is_file($sFile)) {
            require_once($sFile);
            $sClass = 'Ac_'.$sName;
            if(!class_exists($sClass)) {
                exit("Unable to load class '".$sClass."' from file '".$sFile."'.");
            }
            return new $sClass();
        } else {
            exit("Unable to load file '".$sFile."'.");
        }
    }
    
    public function Start() {
    }
}
