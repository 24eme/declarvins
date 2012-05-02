<?php
/**
 * BaseDRMProduits
 * 
 * Base model for DRMProduits


 
 */

abstract class BaseDRMProduits extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'DRM';
       $this->_tree_class_name = 'DRMProduits';
    }
                
}