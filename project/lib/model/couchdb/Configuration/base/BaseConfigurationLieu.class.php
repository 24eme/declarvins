<?php
/**
 * BaseConfigurationLieu
 * 
 * Base model for ConfigurationLieu

 * @property string $libelle
 * @property acCouchdbJson $departements
 * @property acCouchdbJson $couleurs

 * @method string getLibelle()
 * @method string setLibelle()
 * @method acCouchdbJson getDepartements()
 * @method acCouchdbJson setDepartements()
 * @method acCouchdbJson getCouleurs()
 * @method acCouchdbJson setCouleurs()
 
 */

abstract class BaseConfigurationLieu extends _ConfigurationDeclaration {
                
    public function configureTree() {
       $this->_root_class_name = 'Configuration';
       $this->_tree_class_name = 'ConfigurationLieu';
    }
                
}