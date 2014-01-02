<?php


abstract class BaseConfigurationProduitDroit extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'ConfigurationProduit';
       $this->_tree_class_name = 'ConfigurationProduitDroit';
    }
                
}