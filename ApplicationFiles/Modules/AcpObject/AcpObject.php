<?php

class AcpObject extends AcObject {
    public function Object() {
        parent::__construct();
    }
    
    public function Render() {
        return CParser::Parse('AcpObject', 'AcpObject');        
    }
}
