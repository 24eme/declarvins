<?php

/**
 * Model for DRMDetail
 *
 */
class DRMDetail extends BaseDRMDetail {

    protected $_couleur = null;
    protected $_cepage = null;
    
    
    
    /**
     *
     * @return DRMMillesime
     */
    public function getMillesime() {
        return $this->getParent()->getParent();
    }

    /**
     *
     * @return DRMCepage
     */
    public function getCepage() {
        return $this->getMillesime()->getCepage();
    }

    /**
     *
     * @return DRMCouleur
     */
    public function getCouleur() {
        return $this->getCepage()->getCouleur();
    }

    /**
     *
     * @return DRMCouleur
     */
    public function getAppellation() {
        return $this->getCouleur()->getAppellation();
    }

    /*public function updateProduit($produit = null) {
        if (is_null($produit)) {
            $produit = $this->getDocument()->produits->add($this->getAppellation()->getCertification()->getKey())->add($this->getAppellation()->getKey())->add();
        }
        $produit->label = $this->label;
        $produit->label_supplementaire = $this->label_supplementaire;
        $produit->couleur = $this->getCouleur()->getKey();
        $produit->cepage = $this->getCepageValue();
        $produit->millesime = $this->getCepageValue();
        
        return $this;
    }*/
    
    public function getLabelKey() {
    	$key = null;
    	if ($this->label) {
    		$key = implode(',', $this->label->toArray());
    	}
    	return ($key)? $key : DRM::DEFAULT_KEY;
    }

    public function getLabelLibelle() {
        
    }
    
    protected function update($params = array()) {
        parent::update($params);
        $this->total_entrees = $this->getTotalByKey('entrees');
        $this->total_sorties = $this->getTotalByKey('sorties');
        $this->total = $this->total_debut_mois + $this->total_entrees - $this->total_sorties;
    }
    
    private function getTotalByKey($key) {
    	$sum = 0;
    	foreach ($this->get($key) as $k) {
    		$sum += $k;
    	}
    	return $sum;
    }

    public function getTotalDebutMois() {
        if (is_null($this->_get('total_debut_mois'))) {
            return 0;
        } else {
            return $this->_get('total_debut_mois');
        }
    }

    public function isComplete() {
        return $this->total_entrees > 0 || $this->total_sorties > 0;
    }
    
    public function getIdentifiant() {
    	return strtolower($this->getAppellation()->getCertification()."_".$this->getAppellation()->getKey()."_".$this->getCouleur()."_".str_replace('-', '_', $this->getLabelKey()));
    }

    public function __toString() {
        return "<strong>".$this->getAppellation()->getCertification()." - ".$this->getAppellation()."</strong> - ".$this->getCouleur()." - ".$this->getLabelKey();
    }
    
    public function hasContratVrac() {
    	$etablissement = $this->getDocument()->identifiant;
		foreach (VracClient::getInstance()->getAll() as $contrat) {
			if ($contrat->etablissement == $etablissement && (strpos($this->getHash(), $contrat->produit) !== false) && !$this->vrac->exist($contrat->numero)) {
				return true;
            }
        }
        return false;
    }

}