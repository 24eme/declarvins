<?php
/**
 * BaseDRMDroit
 * 
 * Base model for DRMDroit

 * @property float $ratio
 * @property string $code

 * @method float getRatio()
 * @method float setRatio()
 * @method string getCode()
 * @method string setCode()
 
 */

abstract class BaseDRMDroit extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'DRM';
       $this->_tree_class_name = 'DRMDroit';
    }
                
}