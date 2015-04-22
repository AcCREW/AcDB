<?php

class AcpUpdate extends AcObject {
    public function AcpUpdate() {
        parent::__construct();
    }
    
    public function Render() {
        return CParser::Parse('AcpUpdate', 'AcpUpdate', array('Message' => 'Grid!', 'Module' => 'Update'));
    }
}
