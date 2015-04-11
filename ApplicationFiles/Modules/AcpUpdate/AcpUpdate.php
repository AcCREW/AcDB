<?php

class AcpUpdate extends AcObject {
    public function AcpUpdate() {
        parent::__construct();
    }
    
    public function Render() {
        return $this->Parser->Parse('AcpUpdate', 'AcpUpdate', array('Message' => 'Grid!', 'Module' => 'Update'));
    }
}
