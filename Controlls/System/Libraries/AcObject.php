<?php

class AcObject extends stdClass {
    protected $nRecordID = null;
    protected $sObjectName = null;
    
    private $arData = array();
    private $arPendingData = array();

    protected function __construct($nRecordID = null) {
        if(!empty($nRecordID)) {
            $this->nRecordID = $nRecordID;
            $this->Load();
        }
    }
    
    protected function Load() {
        $nRecordID = $this->nRecordID;
        if(empty($nRecordID)) {
            return new Error('RecordID not set!');
        }
        
        return true;
    }
    
    public function Reload() {
        $this->Load();
    }

    public function __get($sName) {
        return $this->GetPropertyValue($sName);
    }
    
    public function GetPropertyValue($sName) {
        return isset($this->_arPendingData[$sName]) ? $this->_arPendingData[$sName] : (isset($this->_arData[$sName]) ? $this->_arData[$sName] : NULL);
    }

    public function __set($sName, $vValue) {
        $this->SetPropertyValue($sName, $vValue);
    }
    
    public function SetPropertyValue($sName, $vValue) {
        $this->arPendingData[$sName] = $vValue;
    }
    
    public function SetData($arData = array()) {
        foreach($arData as $sName => $vValue) {
            $this->__set($sName, $vValue);
        }
    }
    
    public function IsPropertyChanged($sPropertyName) {
		return (array_key_exists($sPropertyName, $this->arPendingData) && array_key_exists($sPropertyName, $this->arData) && $this->arData[$sPropertyName] != $this->GetPropertyValue($sPropertyName));
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