<?php

 
abstract class BaseConfigurationProduit extends acCouchdbDocument {

    public function getDocumentDefinitionModel() {
        return 'ConfigurationProduit';
    }
    
}