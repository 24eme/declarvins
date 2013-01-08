<?php
/**
 * BaseDAIDSDroits
 * 
 * Base model for DAIDSDroits


 
 */

abstract class BaseDAIDSDroits extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'DAIDS';
       $this->_tree_class_name = 'DAIDSDroits';
    }
                
}