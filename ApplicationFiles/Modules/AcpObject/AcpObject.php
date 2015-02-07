<?php

class AcpObject extends AcController {
    public function Object() {
        parent::__construct();
    }
    
    public function Render() {
        return $this->Parser->Parse('AcpObject', 'AcpObject');        
    }
}
