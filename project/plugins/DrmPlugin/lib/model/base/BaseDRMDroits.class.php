<?php
/**
 * BaseDRMDroits
 * 
 * Base model for DRMDroits

 * @property DRMDroit $douane
 * @property DRMDroit $cvo

 * @method DRMDroit getDouane()
 * @method DRMDroit setDouane()
 * @method DRMDroit getCvo()
 * @method DRMDroit setCvo()
 
 */

abstract class BaseDRMDroits extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'DRM';
       $this->_tree_class_name = 'DRMDroits';
    }
                
}