<?php
/**
 * BaseConfigurationCepage
 * 
 * Base model for ConfigurationCepage

 * @property string $libelle
 * @property acCouchdbJson $millesimes

 * @method string getLibelle()
 * @method string setLibelle()
 * @method acCouchdbJson getMillesimes()
 * @method acCouchdbJson setMillesimes()
 
 */

abstract class BaseConfigurationCepage extends _ConfigurationDeclaration {
                
    public function configureTree() {
       $this->_root_class_name = 'Configuration';
       $this->_tree_class_name = 'ConfigurationCepage';
    }
                
}