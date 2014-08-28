<?php

/**
 * 
 *
 * @author Венцислав Кьоровски
 */
class Controller {
   
    public function __construct() {
        foreach(Application::$Class as $sClassName => $Class) {
            $this->$sClassName = &$Class;     
        }
		log_message('debug', "Controller Class Initialized");
    }
    
    
}
