<?php
/**
 * BaseDRMProduits
 * 
 * Base model for DRMProduits


 
 */

abstract class BaseDRMProduits extends _DRMProduit {
                
    public function configureTree() {
       $this->_root_class_name = 'DRM';
       $this->_tree_class_name = 'DRMProduits';
    }
                
}