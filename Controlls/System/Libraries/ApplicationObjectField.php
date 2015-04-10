<?php

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