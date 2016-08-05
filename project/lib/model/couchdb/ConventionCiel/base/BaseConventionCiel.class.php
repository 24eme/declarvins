<?php
/**
 * BaseConventionCiel
 * 
 * Base model for ConventionCiel
 *
 * @property string $_id
 * @property string $_rev
 * @property string $no_convention
 * @property string $raison_sociale
 * @property string $no_operateur
 * @property string $type
 * @property string $nom
 * @property string $prenom
 * @property string $fonction
 * @property string $telephone
 * @property string $email
 * @property string $compte
 * @property string $valide
 * @property acCouchdbJson $etablissements
 * @property acCouchdbJson $habilitations

 * @method string get_id()
 * @method string set_id()
 * @method string get_rev()
 * @method string set_rev()
 * @method string getNoConvention()
 * @method string setNoConvention()
 * @method string getRaisonSociale()
 * @method string setRaisonSociale()
 * @method string getNoOperateur()
 * @method string setNoOperateur()
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
 * @method string getEmail()
 * @method string setEmail()
 * @method string getCompte()
 * @method string setCompte()
 * @method string getValide()
 * @method string setValide()
 * @method acCouchdbJson getEtablissements()
 * @method acCouchdbJson setEtablissements()
 * @method acCouchdbJson getHabilitations()
 * @method acCouchdbJson setHabilitations()
 
 */
 
abstract class BaseConventionCiel extends acCouchdbDocument {

    public function getDocumentDefinitionModel() {
        return 'ConventionCiel';
    }
    
}