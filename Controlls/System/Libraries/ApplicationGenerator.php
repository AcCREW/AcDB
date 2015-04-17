<?php

class ApplicationGenerator extends AcObject {
    
    public function ApplicationGenerator($nRecordID = null) {
        parent::__construct($nRecordID);
        //if(isset($arData['Fields'])) {
        //    $arDataFields = $arData['Fields'];
        //    $arData['Fields'] = array();
        //    foreach($arDataFields as $FieldData) {
        //        $arData['Fields'][] = new ApplicationObjectField($FieldData);
        //    }
        //}
        //$this->ApplicationObject = new ApplicationObject();
        //$this->ApplicationObject->SetData($arData);
        //$rs = $this->DB->Get('SELECT * FROM `#ApplicationObjects` WHERE ObjectName = "'.$this->ApplicationObject->ObjectName.'" LIMIT 1');
        //if($rs != null) {
        //    $this->Exists = true;
        //    $this->ApplicationObject->SetData(unserialize($rs->Data));
        //    var_dump($this->ApplicationObject->HasChangedProperties());
        //}
    }
    
    public function Generate() {
        Dump('asd');
    }
}

/**
 * @property string $ObjectName Object name
 * @property string $ObjectText Object text name
 * @property string $ObjectTableName Table where data will be stored
 * @property array $Fields All object fields
 */
class ApplicationObject extends AcObject {

    public function ApplicationObject($nRecordID = null) {
        parent::__construct($nRecordID);
    }
}

/**
 * @property string $Name Field name
 * @property string $Type Field type
 * @property string $Object foreignKey ObjectName
 * @property double $Decimals Decimals
 */
class ApplicationObjectField extends AcObject {

    public function ApplicationObjectField($nRecordID = null) {
        parent::__construct($nRecordID);
    }
}