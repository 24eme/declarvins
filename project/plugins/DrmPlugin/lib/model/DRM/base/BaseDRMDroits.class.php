<?php
/**
 * BaseDRMDroit
 * 
 * Base model for DRMDroit
 * 
 */

abstract class BaseDRMDroits extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'DRM';
       $this->_tree_class_name = 'DRMDroits';
    }
                
}