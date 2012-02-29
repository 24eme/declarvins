<?php
/**
 * BaseConfigurationCertification
 * 
 * Base model for ConfigurationCertification

 * @property string $libelle
 * @property acCouchdbJson $departements
 * @property acCouchdbJson $interpro
 * @property acCouchdbJson $appellations
 * @property ConfigurationDetail $detail
 * @property ConfigurationDroits $droits

 * @method string getLibelle()
 * @method string setLibelle()
 * @method acCouchdbJson getDepartements()
 * @method acCouchdbJson setDepartements()
 * @method acCouchdbJson getInterpro()
 * @method acCouchdbJson setInterpro()
 * @method acCouchdbJson getAppellations()
 * @method acCouchdbJson setAppellations()
 * @method ConfigurationDetail getDetail()
 * @method ConfigurationDetail setDetail()
 * @method ConfigurationDroits getDroits()
 * @method ConfigurationDroits setDroits()
 
 */

abstract class BaseConfigurationCertification extends _ConfigurationDeclaration {
                
    public function configureTree() {
       $this->_root_class_name = 'Configuration';
       $this->_tree_class_name = 'ConfigurationCertification';
    }
                
}