<?php

class AcpUpdate extends Controller {
    public function AcpUpdate() {
        parent::__construct();
    }
    
    public function Render() {
        return $this->Parser->Parse('AcpUpdate', 'AcpUpdate', array('Message' => 'Grid!', 'Module' => 'Update'));
    }
}
