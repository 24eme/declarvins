<?php
/**
 * BaseDouane
 * 
 * Base model for Douane
 *
 * @property string $_id
 * @property string $_rev
 * @property string $identifiant
 * @property string $nom
 * @property string $type
 * @property string $statut
 * @property string $email

 * @method string get_id()
 * @method string set_id()
 * @method string get_rev()
 * @method string set_rev()
 * @method string getIdentifiant()
 * @method string setIdentifiant()
 * @method string getNom()
 * @method string setNom()
 * @method string getType()
 * @method string setType()
 * @method string getStatut()
 * @method string setStatut()
 * @method string getEmail()
 * @method string setEmail()
 
 */
 
abstract class BaseDouane extends acCouchdbDocument {

    public function getDocumentDefinitionModel() {
        return 'Douane';
    }
    
}