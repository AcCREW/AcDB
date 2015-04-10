<?php

class Index extends Controller {
    public function Index() {
        parent::__construct();
    }
    
    public function Render() {
        return $this->Parser->Parse('Index', 'Index', array('Message' => 'Welcome to AcGenerator!', 'Module' => 'Index'));
    }
}
