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
 * @property integer $rectificative
 * @property acCouchdbJson $produits
 * @property acCouchdbJson $droits
 * @property acCouchdbJson $declaration
 * @property DRMDeclarant $declarant
 * @property acCouchdbJson $decaratif
 * @property string $identifiant
 * @property string $valide
 * @property acCouchdbJson $douane

 * @method string get_id()
 * @method string set_id()
 * @method string get_rev()
 * @method string set_rev()
 * @method string getType()
 * @method string setType()
 * @method string getCampagne()
 * @method string setCampagne()
 * @method integer getRectificative()
 * @method integer setRectificative()
 * @method acCouchdbJson getProduits()
 * @method acCouchdbJson setProduits()
 * @method acCouchdbJson getDroits()
 * @method acCouchdbJson setDroits()
 * @method acCouchdbJson getDeclaration()
 * @method acCouchdbJson setDeclaration()
 * @method DRMDeclarant getDeclarant()
 * @method DRMDeclarant setDeclarant()
 * @method acCouchdbJson getDecaratif()
 * @method acCouchdbJson setDecaratif()
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