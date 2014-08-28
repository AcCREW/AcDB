<?php

class Index extends Controller {
    public function Index() {
        parent::__construct();
    }
    
    public function HelloWorld() {
        var_dump($this->URI->segments);
        return '</br >Hello World!<br /><br />';
    }
}
