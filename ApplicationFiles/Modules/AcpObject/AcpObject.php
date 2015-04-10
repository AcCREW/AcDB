<?php

class AcpObject extends Controller {
    public function Object() {
        parent::__construct();
    }
    
    public function Render() {
        return $this->Parser->Parse('AcpObject', 'AcpObject');        
    }
}
