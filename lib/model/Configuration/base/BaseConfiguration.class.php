<?php
 
abstract class BaseConfiguration extends acCouchdbDocument {

    public function getDocumentDefinitionModel() {
        return 'Configuration';
    }
    
}