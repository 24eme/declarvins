<?php
/**
 * BaseConfigurationCertification
 * 
 * Base model for ConfigurationCertification

 * @property string $libelle
 * @property acCouchdbJson $appellations

 * @method string getLibelle()
 * @method string setLibelle()
 * @method acCouchdbJson getAppellations()
 * @method acCouchdbJson setAppellations()
 
 */

abstract class BaseConfigurationCertification extends _ConfigurationDeclaration {
                
    public function configureTree() {
       $this->_root_class_name = 'Configuration';
       $this->_tree_class_name = 'ConfigurationCertification';
    }
                
}