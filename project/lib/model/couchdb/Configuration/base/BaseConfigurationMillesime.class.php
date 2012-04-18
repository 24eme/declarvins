<?php
/**
 * BaseConfigurationMillesime
 * 
 * Base model for ConfigurationMillesime

 * @property string $libelle
 * @property string $code
 * @property ConfigurationDetail $detail

 * @method string getLibelle()
 * @method string setLibelle()
 * @method string getCode()
 * @method string setCode()
 * @method ConfigurationDetail getDetail()
 * @method ConfigurationDetail setDetail()
 
 */

abstract class BaseConfigurationMillesime extends _ConfigurationDeclaration {
                
    public function configureTree() {
       $this->_root_class_name = 'Configuration';
       $this->_tree_class_name = 'ConfigurationMillesime';
    }
                
}