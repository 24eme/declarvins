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
 * @property acCouchdbJson $produits
 * @property acCouchdbJson $declaration
 * @property DRMDeclarant $declarant
 * @property string $identifiant
 * @property boolean $valide
 * @property acCouchdbJson $douane

 * @method string get_id()
 * @method string set_id()
 * @method string get_rev()
 * @method string set_rev()
 * @method string getType()
 * @method string setType()
 * @method string getCampagne()
 * @method string setCampagne()
 * @method acCouchdbJson getProduits()
 * @method acCouchdbJson setProduits()
 * @method acCouchdbJson getDeclaration()
 * @method acCouchdbJson setDeclaration()
 * @method DRMDeclarant getDeclarant()
 * @method DRMDeclarant setDeclarant()
 * @method string getIdentifiant()
 * @method string setIdentifiant()
 * @method string getValide()
 * @method string setValide()
 * @method acCouchdbJson getDouane()
 * @method acCouchdbJson setDouane()
 
 */
 
abstract class BaseDRM extends acCouchdbDocument {

    public function getDocumentDefinitionModel() {
        return 'DRM';
    }
    
}