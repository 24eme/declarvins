<?php

 
abstract class BaseConfigurationZone extends acCouchdbDocument {

    public function getDocumentDefinitionModel() {
        return 'ConfigurationZone';
    }
    
}