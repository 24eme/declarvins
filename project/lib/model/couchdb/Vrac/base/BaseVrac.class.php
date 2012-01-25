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
 * @property acCouchdbJson $acheteur
 * @property string $etablissement
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
 * @method acCouchdbJson getAcheteur()
 * @method acCouchdbJson setAcheteur()
 * @method string getEtablissement()
 * @method string setEtablissement()
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