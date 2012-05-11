<?php
/**
 * BaseDRMProduitAppellation
 * 
 * Base model for DRMProduitAppellation


 
 */

abstract class BaseDRMProduitAppellation extends _DRMProduit {
                
    public function configureTree() {
       $this->_root_class_name = 'DRM';
       $this->_tree_class_name = 'DRMProduitAppellation';
    }
                
}