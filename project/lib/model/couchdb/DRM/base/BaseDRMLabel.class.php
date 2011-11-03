<?php
/**
 * BaseDRMLabel
 * 
 * Base model for DRMLabel

 * @property sfCouchdbJson $appellations

 * @method sfCouchdbJson getAppellations()
 * @method sfCouchdbJson setAppellations()
 
 */

abstract class BaseDRMLabel extends _DRMTotal {
                
    public function configureTree() {
       $this->_root_class_name = 'DRM';
       $this->_tree_class_name = 'DRMLabel';
    }
                
}