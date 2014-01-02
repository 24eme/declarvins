<?php


abstract class BaseConfigurationProduitGenre extends _ConfigurationProduit {
                
    public function configureTree() {
       $this->_root_class_name = 'ConfigurationProduit';
       $this->_tree_class_name = 'ConfigurationProduitGenre';
    }
                
}