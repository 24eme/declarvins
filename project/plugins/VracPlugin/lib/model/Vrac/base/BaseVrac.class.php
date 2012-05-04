<?php
/**
 * BaseVrac
 * 
 * Base model for Vrac
 *
 * @property string $_id
 * @property string $_rev
 * @property string $type
 * @property string $produit
 * @property string $numero
 * @property string $actif
 * @property string $date_creation
 * @property string $annee
 * @property string $mois
 * @property acCouchdbJson $acheteur
 * @property acCouchdbJson $courtier
 * @property string $etablissement
 * @property float $prix
 * @property float $volume_promis
 * @property float $volume_realise

 * @method string get_id()
 * @method string set_id()
 * @method string get_rev()
 * @method string set_rev()
 * @method string getType()
 * @method string setType()
 * @method string getProduit()
 * @method string setProduit()
 * @method string getNumero()
 * @method string setNumero()
 * @method string getActif()
 * @method string setActif()
 * @method string getDateCreation()
 * @method string setDateCreation()
 * @method string getAnnee()
 * @method string setAnnee()
 * @method string getMois()
 * @method string setMois()
 * @method acCouchdbJson getAcheteur()
 * @method acCouchdbJson setAcheteur()
 * @method acCouchdbJson getCourtier()
 * @method acCouchdbJson setCourtier()
 * @method string getEtablissement()
 * @method string setEtablissement()
 * @method float getPrix()
 * @method float setPrix()
 * @method float getVolumePromis()
 * @method float setVolumePromis()
 * @method float getVolumeRealise()
 * @method float setVolumeRealise()
 
 */
 
abstract class BaseVrac extends acCouchdbDocument {

    public function getDocumentDefinitionModel() {
        return 'Vrac';
    }
    
}