<?php

/**
 * DB Connection
 *
 * DB - MySQLi class databse provider
 *
 * @author Венцислав Кьоровски
 */
class DB {
    public $Host = null;
    public $Username = null;
    public $Password = null;
    public $DB = null;
    public $Port = null;
    public $_mysqli = null;
    
    public static $_instance = null;
    
    public function DB() {
        $this->Host = Application::GetConfig('mysql_host');
        $this->Username = Application::GetConfig('mysql_user');
        $this->Password = Application::GetConfig('mysql_password');
        $this->DB = Application::GetConfig('mysql_db');
        $sPort = Application::GetConfig('mysql_port');
        
        if($sPort == null)
            $this->Port = ini_get('mysqli.default_port');
        else
            $this->Port = $sPort;

        $this->Connect();
        self::$_instance = $this;
    }
    
    public static function GetInstance() {
        return self::$_instance;
    }
    
    public function Connect() {
        $this->_mysqli = new mysqli($this->Host, $this->Username, $this->Password, $this->DB, $this->Port)
            or show_error('There was a problem connecting to the database');

        $this->_mysqli->set_charset('utf8');
    }
    
    public function Get($sQuery) {
        if(empty($sQuery)) {
            return false;
        }
        
        $vResult = $this->_mysqli->query($sQuery);
        
        return $vResult->fetch_object();
    }
    
    public function Execute($sQuery) {
        if(empty($sQuery)) {
            return false;
        }
        
        $this->_mysqli->query($sQuery) or die(mysqli_error($this->_mysqli));
        
        return $this->_mysqli->affected_rows;
    }
}
