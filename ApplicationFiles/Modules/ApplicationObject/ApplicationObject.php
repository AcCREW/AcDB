<?php

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