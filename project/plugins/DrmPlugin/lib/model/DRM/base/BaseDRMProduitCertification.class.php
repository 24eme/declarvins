<?php
/**
 * BaseDRMProduitCertification
 * 
 * Base model for DRMProduitCertification


 
 */

abstract class BaseDRMProduitCertification extends _DRMProduit {
                
    public function configureTree() {
       $this->_root_class_name = 'DRM';
       $this->_tree_class_name = 'DRMProduitCertification';
    }
                
}