<?php
/**
 * BaseConfigurationDroits
 * 
 * Base model for ConfigurationDroits

 * @property ConfigurationDroit $douane
 * @property ConfigurationDroit $cvo

 * @method ConfigurationDroit getDouane()
 * @method ConfigurationDroit setDouane()
 * @method ConfigurationDroit getCvo()
 * @method ConfigurationDroit setCvo()
 
 */

abstract class BaseConfigurationDroits extends acCouchdbDocumentTree {
                
    public function configureTree() {
       $this->_root_class_name = 'Configuration';
       $this->_tree_class_name = 'ConfigurationDroits';
    }
                
}