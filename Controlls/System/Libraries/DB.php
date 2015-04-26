<?php

/**
 * DB Connection
 *
 * DB - MySQLi class databse provider
 *
 * @author Венцислав Кьоровски
 */
class CDB {
    public static $Host = null;
    public static $Username = null;
    public static $Password = null;
    public static $DB = null;
    public static $Port = null;
    
    public static $_instance = null;
    
    public function _Initialize() {
        self::$Host = Application::GetConfig('MYSQL_HOST');
        self::$Username = Application::GetConfig('MYSQL_USER');
        self::$Password = Application::GetConfig('MYSQL_PASSOWRD');
        self::$DB = Application::GetConfig('MYSQL_DB');
        $sPort = Application::GetConfig('MYSQL_PORT');
        
        if($sPort == null)
            self::$Port = ini_get('mysqli.default_port');
        else
            self::$Port = $sPort;

        self::Connect();
    }
    
    public static function GetInstance() {
        return self::$_instance;
    }
    
    public static function Connect() {
        self::$_instance = new mysqli(self::$Host, self::$Username, self::$Password, self::$DB, self::$Port)
            or show_error('There was a problem connecting to the database');

        self::$_instance->set_charset('utf8');
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
