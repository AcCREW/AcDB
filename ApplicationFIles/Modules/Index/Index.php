<?php

class Index extends AcController {
    public function Index() {
        parent::__construct();
    }
    
    public function HelloWorld() {
        var_dump($this->URI->segments);
        return $this->Parser->Parse('Index', 'Index');
    }
}
