<?php

class sfCouchdbJsonDefinitionFieldArrayCollection extends sfCouchdbJsonDefinitionFieldCollection {
    public function __construct($name, $required, $model, $hash, $collection_class = 'sfCouchdbJson', $inheritance = null) {
        return parent::__construct($name, $required, $model, $hash, $collection_class, $inheritance);
    }

    public function getDefaultValue($couchdb_document, $hash) {
        $json_collection = parent::getDefaultValue($couchdb_document, $hash);
        $json_collection->setIsArray(true);
        return $json_collection;
    }
}