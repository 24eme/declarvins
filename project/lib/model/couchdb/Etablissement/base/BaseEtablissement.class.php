<?php
/**
 * BaseEtablissement
 * 
 * Base model for Etablissement
 *
 * @property string $identifiant
 * @property string $nom
 * @property string $type
 * @property string $statut
 * @property string $_id
 * @property string $_rev
 * @property string $interpro
 * @property string $compte
 * @property sfCouchdbJson $droits
 * @property string $num_interne
 * @property string $siret
 * @property string $cni
 * @property string $cvi
 * @property string $no_accises
 * @property string $no_tva_intracommunautaire
 * @property string $famille
 * @property string $sous_famille
 * @property string $email
 * @property string $telephone
 * @property string $fax
 * @property sfCouchdbJson $siege
 * @property sfCouchdbJson $comptabilite
 * @property string $service_douane

 * @method string getIdentifiant()
 * @method string setIdentifiant()
 * @method string getNom()
 * @method string setNom()
 * @method string getType()
 * @method string setType()
 * @method string getStatut()
 * @method string setStatut()
 * @method string get_id()
 * @method string set_id()
 * @method string get_rev()
 * @method string set_rev()
 * @method string getInterpro()
 * @method string setInterpro()
 * @method string getCompte()
 * @method string setCompte()
 * @method sfCouchdbJson getDroits()
 * @method sfCouchdbJson setDroits()
 * @method string getNumInterne()
 * @method string setNumInterne()
 * @method string getSiret()
 * @method string setSiret()
 * @method string getCni()
 * @method string setCni()
 * @method string getCvi()
 * @method string setCvi()
 * @method string getNoAccises()
 * @method string setNoAccises()
 * @method string getNoTvaIntracommunautaire()
 * @method string setNoTvaIntracommunautaire()
 * @method string getFamille()
 * @method string setFamille()
 * @method string getSousFamille()
 * @method string setSousFamille()
 * @method string getEmail()
 * @method string setEmail()
 * @method string getTelephone()
 * @method string setTelephone()
 * @method string getFax()
 * @method string setFax()
 * @method sfCouchdbJson getSiege()
 * @method sfCouchdbJson setSiege()
 * @method sfCouchdbJson getComptabilite()
 * @method sfCouchdbJson setComptabilite()
 * @method string getServiceDouane()
 * @method string setServiceDouane()
 
 */
 
abstract class BaseEtablissement extends sfCouchdbDocument {

    public function getDocumentDefinitionModel() {
        return 'Etablissement';
    }
    
}