<?php
/**
 * BaseConfigurationDroit
 * 
 * Base model for ConfigurationDroit

 * @property string $date
 * @property float $ratio
 * @property string $code

 * @method string getDate()
 * @method string setDate()
 * @method float getRatio()
 * @method float setRatio()
 * @method string getCode()
 * @method string setCode()
 
 */

abstract class BaseConfigurationDroit extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'Configuration';
       $this->_tree_class_name = 'ConfigurationDroit';
    }
                
}