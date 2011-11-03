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
 * @property sfCouchdbJson $declaration

 * @method string get_id()
 * @method string set_id()
 * @method string get_rev()
 * @method string set_rev()
 * @method string getType()
 * @method string setType()
 * @method string getCampagne()
 * @method string setCampagne()
 * @method sfCouchdbJson getDeclaration()
 * @method sfCouchdbJson setDeclaration()
 
 */
 
abstract class BaseConfiguration extends sfCouchdbDocument {

    public function getDocumentDefinitionModel() {
        return 'Configuration';
    }
    
}