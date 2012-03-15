<?php
/**
 * BaseDRMProduitAppellation
 * 
 * Base model for DRMProduitAppellation


 
 */

abstract class BaseDRMProduitAppellation extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'DRM';
       $this->_tree_class_name = 'DRMProduitAppellation';
    }
                
}