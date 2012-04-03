<?php
/**
 * BaseConfigurationAppellation
 * 
 * Base model for ConfigurationAppellation

 * @property string $libelle
 * @property string $code
 * @property acCouchdbJson $interpro
 * @property acCouchdbJson $departements
 * @property acCouchdbJson $lieux

 * @method string getLibelle()
 * @method string setLibelle()
 * @method string getCode()
 * @method string setCode()
 * @method acCouchdbJson getInterpro()
 * @method acCouchdbJson setInterpro()
 * @method acCouchdbJson getDepartements()
 * @method acCouchdbJson setDepartements()
 * @method acCouchdbJson getLieux()
 * @method acCouchdbJson setLieux()
 
 */

abstract class BaseConfigurationAppellation extends _ConfigurationDeclaration {
                
    public function configureTree() {
       $this->_root_class_name = 'Configuration';
       $this->_tree_class_name = 'ConfigurationAppellation';
    }
                
}