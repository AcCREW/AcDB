<?php

error_reporting(E_ALL);

function Generate() {
    if(sizeof($_POST) <= 0) {
        return;
    }

    if(!isset($_POST['InitData'])) {
        return;
    }

    return new Generator($_POST);
}

class AcObject extends stdClass {
    private $arData = array();
    private $arPendingData = array();

    protected function __construct($arData = array()) {
        $this->arData = $arData;
    }

    public function __get($sKey) {
        return isset($this->arPendingData[$sKey]) ? $this->arPendingData[$sKey] : (isset($this->arData[$sKey]) ? $this->arData[$sKey] : null);
    }

    public function __set($sKey, $vValue) {
        $this->arPendingData[$sKey] = $vValue;
    }
    
    public function SetData($arData = array()) {
        foreach($arData as $sKey => $vValue) {
            $this->__set($sKey, $vValue);
        }
    }

    public function HasChangedProperties() {
        $arChangedProperties = array();

        foreach($this->arPendingData as $vPendingKey => $vPendingValue) {
            if(!isset($this->arData[$vPendingKey]) || $this->arData[$vPendingKey] != $this->arPendingData[$vPendingKey]) {
                $arChangedProperties[] = $vPendingKey;
            }
        }
        
        return sizeof($arChangedProperties) == 0 ? false : $arChangedProperties;
    }
}

/**
 * @property string $ObjectName Стриктно име на обекта
 * @property string $ObjectText Име на обекта
 * @property string $ObjectTableName Име на таблицата която ще използва обекта
 * @property array $Fields Масив с полетата които ще съдържа обекта
 */
class ApplicationObject extends AcObject {

    public function ApplicationObject($arData = array()) {
        parent::__construct($arData);
    }
}

/**
 * @property string $Name Име на полето
 * @property string $Type Тип на полето
 * @property string $Object Обект към който е свързано полето (важи само за тип foreignKey)
 * @property decimal $Decimals Колко знака след запетаята да се показват (default: 2, само за типове които я използват)
 */
class ApplicationObjectField extends AcObject {

    public function ApplicationObjectField($arData = array()) {
        parent::__construct($arData);
    }
}

class Generator {
    private $ApplicationObject = null;
    private $DB = null;
    private $Exists = false;

    public function Generator($arData) {
        $this->DB = new DB('localhost', 'root', '', 'acdb');
        if(isset($arData['Fields'])) {
            $arDataFields = $arData['Fields'];
            $arData['Fields'] = array();
            foreach($arDataFields as $FieldData) {
                $arData['Fields'][] = new ApplicationObjectField($FieldData);
            }
        }
        $this->ApplicationObject = new ApplicationObject($arData);
        $rs = $this->DB->Get('SELECT * FROM `#applicationobjects` WHERE ObjectName = "'.$this->ApplicationObject->ObjectName.'" LIMIT 1');
        if($rs != null) {
            $this->Exists = true;
            $this->ApplicationObject->SetData(unserialize($rs->Data));
            var_dump($this->ApplicationObject->HasChangedProperties());
        }
    }
}

class DB { 
    public $Host = null;
    public $Username = null;
    public $Password = null;
    public $DB = null;
    public $Port = null;
    public $_mysqli = null;
    
    public static $_instance = null;
    
    
    public function DB($sHost, $sUsername, $sPassword, $sDB, $sPort = null) {
        $this->Host = $sHost;
        $this->Username = $sUsername;
        $this->Password = $sPassword;
        $this->DB = $sDB;
        
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
            or die('There was a problem connecting to the database');

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

Generate();
