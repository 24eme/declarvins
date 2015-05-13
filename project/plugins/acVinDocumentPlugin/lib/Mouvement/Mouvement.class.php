<?php

abstract class Mouvement extends acCouchdbDocumentTree
{
    const TYPE_HASH_CONTRAT_VRAC = 'vrac_details';
    const TYPE_HASH_CONTRAT_RAISIN = VracClient::TYPE_TRANSACTION_RAISINS;
    const TYPE_HASH_CONTRAT_MOUT = VracClient::TYPE_TRANSACTION_MOUTS;

    public function setProduitHash($value) {
        $this->_set('produit_hash',  $value);
        $this->produit_libelle = $this->getProduitConfig()->getLibelleFormat(array(), "%format_libelle%");
    }

    public function facturer() {
        if($this->isFacturable()) {
            $this->facture = 1;
        }
    }

    public function defacturer() {
        $this->facture = 0;
    }

    public function getMD5Key() {
        $key = $this->getDocument()->identifiant . $this->produit_hash . $this->type_hash . $this->detail_identifiant;
        $key.= uniqid();
        
        return md5($key);
    }

    public function isFacturable() {        
        return $this->facturable;
    }

    public function isFacture() {
        if (!$this->isFacturable()) {

            return true;
        }

        return !$this->facture;
    }

    public function isNonFacture() {

        if (!$this->isFacturable()) {

            return true;
        }

        return !$this->facture;
    }

    public function setVolume($v) {
      if ($this->volume === 0 && $v) {
	throw new sfException('PB Facturable : plus capable de savoir si le mouvement est facturable ou non');
      }
      if (!$v)
	$this->facturable = 0;
      return $this->_set('volume', $v);
    }

    public function setCVO($cvo) {
      if ($this->cvo === 0 && $cvo) {
	throw new sfException('PB Facturable : plus capable de savoir si le mouvement est facturable ou non');
      }
      if (!$cvo)
	$this->facturable = 0;
      return $this->_set('cvo', $cvo);
    }

    public function isVrac() {

        return $this->vrac_numero;
    }

    public function getVrac() {

        if (!$this->isVrac()) {
            return null;
        }

        $vrac = VracClient::getInstance()->findByNumContrat($this->vrac_numero);

        if (!$vrac) {

            throw new sfException(sprintf("Le contrat '%s' n'a pas été trouvé", $this->vrac_numero));
        }

        return $vrac;
    }

    public function getProduitConfig() {
        
        $config_client = ConfigurationClient::getCurrent();        
        if (method_exists($config_client,'getConfigurationProduit')){
            return $config_client->getConfigurationProduit($this->produit_hash);
        }       
        return ConfigurationClient::getCurrent()->get($this->produit_hash);
        
    }
    
    public function getDocId() {

        return $this->getDocument()->_id;
    }
   

    public function getId() {

        return $this->getDocument()->_id.'/mouvements/'.$this->getKey();
    }

    public function getType() {

        return $this->getDocument()->getType();
    }

    public function getNumeroArchive() {
        if(!$this->isVrac()) {
            return;
        }

        return $this->detail_libelle;
    }
}