<?php
/**
 * BaseDRMDetail
 * 
 * Base model for DRMDetail

 * @property string $entrees
 * @property string $sorties

 * @method string getEntrees()
 * @method string setEntrees()
 * @method string getSorties()
 * @method string setSorties()
 
 */

abstract class BaseDRMDetail extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'DRM';
       $this->_tree_class_name = 'DRMDetail';
    }
                
}