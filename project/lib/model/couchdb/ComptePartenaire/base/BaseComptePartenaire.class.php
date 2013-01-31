<?php
/**
 * BaseComptePartenaire
 * 
 * Base model for CompteVirtuel
 *
 * @property string $login
 * @property string $mot_de_passe
 * @property string $email
 * @property string $statut
 * @property acCouchdbJson $droits
 * @property string $_id
 * @property string $_rev
 * @property string $type
 * @property string $nom
 * @property string $commune
 * @property string $code_postal
 * @property string $interpro
 * @property string $prenom
 * @property string $telephone
 * @property string $fax

 * @method string getLogin()
 * @method string setLogin()
 * @method string getMotDePasse()
 * @method string setMotDePasse()
 * @method string getEmail()
 * @method string setEmail()
 * @method string getStatut()
 * @method string setStatut()
 * @method acCouchdbJson getDroits()
 * @method acCouchdbJson setDroits()
 * @method string get_id()
 * @method string set_id()
 * @method string get_rev()
 * @method string set_rev()
 * @method string getType()
 * @method string setType()
 * @method string getNom()
 * @method string setNom()
 * @method string getCommune()
 * @method string setCommune()
 * @method string getCodePostal()
 * @method string setCodePostal()
 * @method string getInterpro()
 * @method string setInterpro()
 * @method string getPrenom()
 * @method string setPrenom()
 * @method string getTelephone()
 * @method string setTelephone()
 * @method string getFax()
 * @method string setFax()
 
 */
 
abstract class BaseComptePartenaire extends CompteVirtuel {

    public function getDocumentDefinitionModel() {
        return 'ComptePartenaire';
    }
    
}