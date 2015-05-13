<?php
/**
 * BaseConfigurationVracEtapes
 * 
 * Base model for ConfigurationVracEtapes


 
 */

abstract class BaseConfigurationVracEtapes extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'Configuration';
       $this->_tree_class_name = 'ConfigurationVracEtapes';
    }
                
}