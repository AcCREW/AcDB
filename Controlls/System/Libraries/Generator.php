<?php

class Generator {
    private $ApplicationObject = null;
    private $DB = null;
    private $Exists = false;

    public function Generator($arData) {
        $this->DB = new DB('localhost', 'root', '', 'AcDB');
        if(isset($arData['Fields'])) {
            $arDataFields = $arData['Fields'];
            $arData['Fields'] = array();
            foreach($arDataFields as $FieldData) {
                $arData['Fields'][] = new ApplicationObjectField($FieldData);
            }
        }
        $this->ApplicationObject = new ApplicationObject();
        $this->ApplicationObject->SetData($arData);
        $rs = $this->DB->Get('SELECT * FROM `#ApplicationObjects` WHERE ObjectName = "'.$this->ApplicationObject->ObjectName.'" LIMIT 1');
        if($rs != null) {
            $this->Exists = true;
            $this->ApplicationObject->SetData(unserialize($rs->Data));
            var_dump($this->ApplicationObject->HasChangedProperties());
        }
    }
}