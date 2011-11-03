<?php
/**
 * BaseConfigurationLabel
 * 
 * Base model for ConfigurationLabel

 * @property sfCouchdbJson $appellations

 * @method sfCouchdbJson getAppellations()
 * @method sfCouchdbJson setAppellations()
 
 */

abstract class BaseConfigurationLabel extends _ConfigurationDeclaration {
                
    public function configureTree() {
       $this->_root_class_name = 'Configuration';
       $this->_tree_class_name = 'ConfigurationLabel';
    }
                
}