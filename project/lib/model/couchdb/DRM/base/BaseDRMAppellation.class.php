<?php
/**
 * BaseDRMAppellation
 * 
 * Base model for DRMAppellation

 * @property string $libelle
 * @property acCouchdbJson $couleurs

 * @method string getLibelle()
 * @method string setLibelle()
 * @method acCouchdbJson getCouleurs()
 * @method acCouchdbJson setCouleurs()
 
 */

abstract class BaseDRMAppellation extends _DRMTotal {
                
    public function configureTree() {
       $this->_root_class_name = 'DRM';
       $this->_tree_class_name = 'DRMAppellation';
    }
                
}