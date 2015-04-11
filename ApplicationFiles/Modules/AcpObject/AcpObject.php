<?php

class AcpObject extends AcObject {
    public function Object() {
        parent::__construct();
    }
    
    public function Render() {
        return $this->Parser->Parse('AcpObject', 'AcpObject');        
    }
}
