<?php

class AcpUpdate extends AcController {
    public function AcpUpdate() {
        parent::__construct();
    }
    
    public function Render() {
        return $this->Parser->Parse('AcpUpdate', 'AcpUpdate', array('Message' => 'Welcome to AcGenerator!', 'Module' => 'Index'));
    }
}
