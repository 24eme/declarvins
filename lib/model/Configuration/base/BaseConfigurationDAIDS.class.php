<?php
/**
 * BaseConfigurationDAIDS
 * 
 * Base model for ConfigurationDAIDS

 * @property acCouchdbJson $stocks_moyen

 * @method acCouchdbJson getStocksMoyen()
 * @method acCouchdbJson setStocksMoyen()
 
 */

abstract class BaseConfigurationDAIDS extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'Configuration';
       $this->_tree_class_name = 'ConfigurationDAIDS';
    }
                
}