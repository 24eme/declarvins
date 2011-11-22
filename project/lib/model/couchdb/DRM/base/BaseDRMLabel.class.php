<?php
/**
 * BaseDRMLabel
 * 
 * Base model for DRMLabel

 * @property acCouchdbJson $appellations

 * @method acCouchdbJson getAppellations()
 * @method acCouchdbJson setAppellations()
 
 */

abstract class BaseDRMLabel extends _DRMTotal {
                
    public function configureTree() {
       $this->_root_class_name = 'DRM';
       $this->_tree_class_name = 'DRMLabel';
    }
                
}