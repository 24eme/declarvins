<?php
/**
 * BaseConfigurationMillesime
 * 
 * Base model for ConfigurationMillesime

 * @property string $libelle

 * @method string getLibelle()
 * @method string setLibelle()
 
 */

abstract class BaseConfigurationMillesime extends _ConfigurationDeclaration {
                
    public function configureTree() {
       $this->_root_class_name = 'Configuration';
       $this->_tree_class_name = 'ConfigurationMillesime';
    }
                
}