<?php
/**
 * BaseDRMDroit
 * 
 * Base model for DRMDroit

 * @property float $taux
 * @property string $code

 * @method float getTaux()
 * @method float setTaux()
 * @method string getCode()
 * @method string setCode()
 
 */

abstract class BaseDRMDroit extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'DRM';
       $this->_tree_class_name = 'DRMDroit';
    }
                
}