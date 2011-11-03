<?php

class sfCouchdbJsonDefinitionFieldMultipleCollection extends sfCouchdbJsonDefinitionFieldCollection {
    public function __construct($model, $hash, $collection_class = 'sfCouchdbJson', $inheritance = null) {
        $definition = parent::__construct('*', false, $model, $hash, $collection_class, $inheritance);
        $this->is_multiple = true;
        return $definition;
    }
}