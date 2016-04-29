<?php
/**
 * BaseDRMESDetailCrd
 * 
 * Base model for DRMESDetailCrd

 
 */

abstract class BaseDRMESDetailCrd extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'DRM';
       $this->_tree_class_name = 'DRMESDetailCrd';
    }
                
}