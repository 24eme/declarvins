<?php
/**
 * BaseDRM
 * 
 * Base model for DRM
 *
 * @property string $_id
 * @property string $_rev
 * @property string $type
 * @property string $campagne
 * @property sfCouchdbJson $declaration
 * @property DRMDeclarant $declarant
 * @property string $identifiant

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
 * @method DRMDeclarant getDeclarant()
 * @method DRMDeclarant setDeclarant()
 * @method string getIdentifiant()
 * @method string setIdentifiant()
 
 */
 
abstract class BaseDRM extends sfCouchdbDocument {

    public function getDocumentDefinitionModel() {
        return 'DRM';
    }
    
}