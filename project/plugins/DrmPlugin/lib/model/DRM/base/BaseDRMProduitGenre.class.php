<?php
/**
 * BaseDRMProduitGenre
 * 
 * Base model for DRMProduitGenre


 
 */

abstract class BaseDRMProduitGenre extends _DRMProduit {
                
    public function configureTree() {
       $this->_root_class_name = 'DRM';
       $this->_tree_class_name = 'DRMProduitGenre';
    }
                
}