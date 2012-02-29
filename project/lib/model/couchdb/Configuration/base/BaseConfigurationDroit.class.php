<?php
/**
 * BaseConfigurationDroit
 * 
 * Base model for ConfigurationDroit


 
 */

abstract class BaseConfigurationDroit extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'Configuration';
       $this->_tree_class_name = 'ConfigurationDroit';
    }
                
}