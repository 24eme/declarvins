<?php

class sfCouchdbJsonDefinitionFieldMultipleArrayCollection extends sfCouchdbJsonDefinitionFieldArrayCollection {
    public function __construct($model, $hash, $collection_class = 'sfCouchdbJson') {
        $definition = parent::__construct('*', false, $model, $hash, $collection_class);
        $this->is_multiple = true;
        return $definition;
    }

    
}