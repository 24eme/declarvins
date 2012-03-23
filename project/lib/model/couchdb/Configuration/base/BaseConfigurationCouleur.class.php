<?php
/**
 * BaseConfigurationCouleur
 * 
 * Base model for ConfigurationCouleur

 * @property string $libelle
 * @property acCouchdbJson $interpro
 * @property acCouchdbJson $departements
 * @property acCouchdbJson $cepages

 * @method string getLibelle()
 * @method string setLibelle()
 * @method acCouchdbJson getInterpro()
 * @method acCouchdbJson setInterpro()
 * @method acCouchdbJson getDepartements()
 * @method acCouchdbJson setDepartements()
 * @method acCouchdbJson getCepages()
 * @method acCouchdbJson setCepages()
 
 */

abstract class BaseConfigurationCouleur extends _ConfigurationDeclaration {
                
    public function configureTree() {
       $this->_root_class_name = 'Configuration';
       $this->_tree_class_name = 'ConfigurationCouleur';
    }
                
}