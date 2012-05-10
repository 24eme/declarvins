<?php
/**
 * BaseContrat
 * 
 * Base model for Contrat
 *
 * @property string $_id
 * @property string $_rev
 * @property string $no_contrat
 * @property string $type
 * @property string $nom
 * @property string $prenom
 * @property string $fonction
 * @property string $telephone
 * @property string $fax
 * @property string $compte
 * @property acCouchdbJson $etablissements

 * @method string get_id()
 * @method string set_id()
 * @method string get_rev()
 * @method string set_rev()
 * @method string getNoContrat()
 * @method string setNoContrat()
 * @method string getType()
 * @method string setType()
 * @method string getNom()
 * @method string setNom()
 * @method string getPrenom()
 * @method string setPrenom()
 * @method string getFonction()
 * @method string setFonction()
 * @method string getTelephone()
 * @method string setTelephone()
 * @method string getFax()
 * @method string setFax()
 * @method string getCompte()
 * @method string setCompte()
 * @method acCouchdbJson getEtablissements()
 * @method acCouchdbJson setEtablissements()
 
 */
 
abstract class BaseContrat extends acCouchdbDocument {

    public function getDocumentDefinitionModel() {
        return 'Contrat';
    }
    
}