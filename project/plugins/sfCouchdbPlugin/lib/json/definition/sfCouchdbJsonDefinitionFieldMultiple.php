<?php

class sfCouchdbJsonDefinitionFieldMultiple extends sfCouchdbJsonDefinitionField {
    public function __construct($type = self::TYPE_STRING) {
        parent::__construct('*', $type, false);
        $this->is_multiple = true;
    }
}