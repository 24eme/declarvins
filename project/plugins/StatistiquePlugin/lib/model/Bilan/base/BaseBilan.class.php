<?php
/**
 * BaseBilan
 * 
 * Base model for Bilan
 *
 * @property string $_id
 * @property string $_rev
 * @property string $type
 * @property string $identifiant
 * @property string $type_bilan
 * @property acCouchdbJson $etablissement
 * @property acCouchdbJson $campagnes

 * @method string get_id()
 * @method string set_id()
 * @method string get_rev()
 * @method string set_rev()
 * @method string getType()
 * @method string setType()
 * @method string getIdentifiant()
 * @method string setIdentifiant()
 * @method string getTypeBilan()
 * @method string setTypeBilan()
 * @method acCouchdbJson getEtablissement()
 * @method acCouchdbJson setEtablissement()
 * @method acCouchdbJson getCampagnes()
 * @method acCouchdbJson setCampagnes()
 
 */
 
abstract class BaseBilan extends acCouchdbDocument {

    public function getDocumentDefinitionModel() {
        return 'Bilan';
    }
    
}