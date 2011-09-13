<?php

class sfCouchdbJsonDefinitionFieldArrayCollection extends sfCouchdbJsonDefinitionFieldCollection {
    public function __construct($name, $required, $model, $hash, $collection_class = 'sfCouchdbJson') {
        return parent::__construct($name, $required, $model, $hash, $collection_class);
    }

    public function getDefaultValue($couchdb_document, $hash) {
        $json_collection = parent::getDefaultValue($couchdb_document, $hash);
        $json_collection->setIsArray(true);
        return $json_collection;
    }
}