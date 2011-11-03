<?php
/**
 * BaseConfigurationAppellation
 * 
 * Base model for ConfigurationAppellation

 * @property string $libelle
 * @property sfCouchdbJson $couleurs

 * @method string getLibelle()
 * @method string setLibelle()
 * @method sfCouchdbJson getCouleurs()
 * @method sfCouchdbJson setCouleurs()
 
 */

abstract class BaseConfigurationAppellation extends _ConfigurationDeclaration {
                
    public function configureTree() {
       $this->_root_class_name = 'Configuration';
       $this->_tree_class_name = 'ConfigurationAppellation';
    }
                
}