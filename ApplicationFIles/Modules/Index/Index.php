<?php

class Index extends AcController {
    public function Index() {
        parent::__construct();
    }
    
    public function HelloWorld() {
        return $this->Parser->Parse('Index', 'Index', array('message' => 'Hello World !'));
    }
}
