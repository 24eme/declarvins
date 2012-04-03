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
 * @property string $precedente
 * @property integer $rectificative
 * @property acCouchdbJson $produits
 * @property acCouchdbJson $droits
 * @property acCouchdbJson $declaration
 * @property DRMDeclarant $declarant
 * @property acCouchdbJson $declaratif
 * @property string $identifiant
 * @property string $mode_de_saisie
 * @property acCouchdbJson $interpros
 * @property acCouchdbJson $valide
 * @property acCouchdbJson $douane

 * @method string get_id()
 * @method string set_id()
 * @method string get_rev()
 * @method string set_rev()
 * @method string getType()
 * @method string setType()
 * @method string getCampagne()
 * @method string setCampagne()
 * @method string getPrecedente()
 * @method string setPrecedente()
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
 * @method acCouchdbJson getDeclaratif()
 * @method acCouchdbJson setDeclaratif()
 * @method string getIdentifiant()
 * @method string setIdentifiant()
 * @method string getModeDeSaisie()
 * @method string setModeDeSaisie()
 * @method acCouchdbJson getInterpros()
 * @method acCouchdbJson setInterpros()
 * @method acCouchdbJson getValide()
 * @method acCouchdbJson setValide()
 * @method acCouchdbJson getDouane()
 * @method acCouchdbJson setDouane()
 
 */
 
abstract class BaseDRM extends acCouchdbDocument {

    public function getDocumentDefinitionModel() {
        return 'DRM';
    }
    
}