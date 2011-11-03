<?php
/**
 * BaseDRMAppellation
 * 
 * Base model for DRMAppellation

 * @property string $libelle
 * @property sfCouchdbJson $couleurs

 * @method string getLibelle()
 * @method string setLibelle()
 * @method sfCouchdbJson getCouleurs()
 * @method sfCouchdbJson setCouleurs()
 
 */

abstract class BaseDRMAppellation extends _DRMTotal {
                
    public function configureTree() {
       $this->_root_class_name = 'DRM';
       $this->_tree_class_name = 'DRMAppellation';
    }
                
}