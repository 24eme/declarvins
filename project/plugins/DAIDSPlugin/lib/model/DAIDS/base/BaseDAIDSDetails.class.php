<?php
/**
 * BaseDAIDSDetails
 * 
 * Base model for DAIDSDetails


 
 */

abstract class BaseDAIDSDetails extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'DAIDS';
       $this->_tree_class_name = 'DAIDSDetails';
    }
                
}