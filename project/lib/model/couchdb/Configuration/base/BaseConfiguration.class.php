<?php
/**
 * BaseConfiguration
 * 
 * Base model for Configuration
 *
 * @property string $_id
 * @property string $_rev
 * @property string $type
 * @property string $campagne
 * @property acCouchdbJson $label
 * @property acCouchdbJson $declaration

 * @method string get_id()
 * @method string set_id()
 * @method string get_rev()
 * @method string set_rev()
 * @method string getType()
 * @method string setType()
 * @method string getCampagne()
 * @method string setCampagne()
 * @method acCouchdbJson getLabel()
 * @method acCouchdbJson setLabel()
 * @method acCouchdbJson getDeclaration()
 * @method acCouchdbJson setDeclaration()
 
 */
 
abstract class BaseConfiguration extends acCouchdbDocument {

    public function getDocumentDefinitionModel() {
        return 'Configuration';
    }
    
}