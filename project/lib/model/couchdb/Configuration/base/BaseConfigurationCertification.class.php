<?php
/**
 * BaseConfigurationCertification
 * 
 * Base model for ConfigurationCertification

 * @property string $libelle
 * @property acCouchdbJson $interpro
 * @property acCouchdbJson $departements
 * @property acCouchdbJson $genres
 * @property ConfigurationDetail $detail

 * @method string getLibelle()
 * @method string setLibelle()
 * @method acCouchdbJson getInterpro()
 * @method acCouchdbJson setInterpro()
 * @method acCouchdbJson getDepartements()
 * @method acCouchdbJson setDepartements()
 * @method acCouchdbJson getGenres()
 * @method acCouchdbJson setGenres()
 * @method ConfigurationDetail getDetail()
 * @method ConfigurationDetail setDetail()
 
 */

abstract class BaseConfigurationCertification extends _ConfigurationDeclaration {
                
    public function configureTree() {
       $this->_root_class_name = 'Configuration';
       $this->_tree_class_name = 'ConfigurationCertification';
    }
                
}