<?php
/**
 * BaseAlerte
 * 
 * Base model for Alerte
 *
 * @property string $_id
 * @property string $_rev
 * @property string $type
 * @property string $etablissement_identifiant
 * @property acCouchdbJson $alertes
 * @property string $derniere_detection

 * @method string get_id()
 * @method string set_id()
 * @method string get_rev()
 * @method string set_rev()
 * @method string getType()
 * @method string setType()
 * @method string getEtablissementIdentifiant()
 * @method string setEtablissementIdentifiant()
 * @method acCouchdbJson getAlertes()
 * @method acCouchdbJson setAlertes()
 * @method string getDerniereDetection()
 * @method string setDerniereDetection()
 
 */
 
abstract class BaseAlerte extends acCouchdbDocument {

    public function getDocumentDefinitionModel() {
        return 'Alerte';
    }
    
}