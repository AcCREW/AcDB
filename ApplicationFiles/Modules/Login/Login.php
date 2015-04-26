<?php

class Login extends AcObject {
    public function Login() {
        parent::__construct();
    }
    
    public function Render() {
        return CParser::Parse('Login', 'Login', array('Message' => 'Welcome to AcGenerator!'));
    }
}
