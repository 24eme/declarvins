<?php
/**
 * BaseDRMCouleur
 * 
 * Base model for DRMCouleur

 * @property string $libelle
 * @property sfCouchdbJson $details

 * @method string getLibelle()
 * @method string setLibelle()
 * @method sfCouchdbJson getDetails()
 * @method sfCouchdbJson setDetails()
 
 */

abstract class BaseDRMCouleur extends _DRMTotal {
                
    public function configureTree() {
       $this->_root_class_name = 'DRM';
       $this->_tree_class_name = 'DRMCouleur';
    }
                
}