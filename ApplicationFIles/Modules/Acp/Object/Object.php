<?php

class Object extends AcController {
    public function Object() {
        parent::__construct();
    }
    
    public function Render() {
        return $this->Parser->Parse('Object', 'Acp/Object', array('Module' => $this->Module));        
    }
}
