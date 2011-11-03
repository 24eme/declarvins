<?php
/**
 * BaseConfigurationCouleur
 * 
 * Base model for ConfigurationCouleur

 * @property string $libelle

 * @method string getLibelle()
 * @method string setLibelle()
 
 */

abstract class BaseConfigurationCouleur extends _ConfigurationDeclaration {
                
    public function configureTree() {
       $this->_root_class_name = 'Configuration';
       $this->_tree_class_name = 'ConfigurationCouleur';
    }
                
}