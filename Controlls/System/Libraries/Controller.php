<?php

/**
 * 
 *
 * @author Венцислав Кьоровски
 */
class AcController extends stdClass{
   
    public function __construct() {
        foreach(array_keys(Application::$Class) as $sClassName) {
            $this->$sClassName = Application::LoadLibrary($sClassName);     
        }
		log_message('debug', "Controller Class Initialized");
    }
    
    
}
