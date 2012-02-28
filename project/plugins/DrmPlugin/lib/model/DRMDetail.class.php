<?php

/**
 * Model for DRMDetail
 *
 */
class DRMDetail extends BaseDRMDetail {
    
    public function getLibelles() {

        return $this->getMillesime()->getConfig()->getLibelles();
    }
    
    public function getConfig() {
    	return ConfigurationClient::getCurrent()->declaration->certifications->get($this->getCertification()->getKey())->detail;
    }
    
    public function getCertification() {
    	return $this->getAppellation()->getCertification();
    }

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
     * @return DRMLieu
     */
    public function getLieu() {
        return $this->getCouleur()->getLieu();
    }

    /**
     *
     * @return DRMAppellation
     */
    public function getAppellation() {
        return $this->getLieu()->getAppellation();
    }

    public function getLabelKeyFromValues($values) {
        
    }
    
    public function getLabelKey() {
    	$key = null;
    	if ($this->label) {
    		$key = implode('-', $this->label->toArray());
    	}
    	return ($key) ? $key : DRM::DEFAULT_KEY;
    }

    public function getLabelLibelles() {
        $libelles = array(); 
        foreach($this->label as $key) {
            $libelles[] = ConfigurationClient::getCurrent()->label[$key];
        }

        return $libelles;
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
    	return strtolower(str_replace($this->getDocument()->declaration->getHash(), '', str_replace('/', '_', $this->getHash())));
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
    
    public function getContratsVrac() {
    	$etablissement = $this->getDocument()->identifiant;
    	$contrats = array();
		foreach (VracClient::getInstance()->getAll() as $contrat) {
			if ($contrat->etablissement == $etablissement && (strpos($this->getHash(), $contrat->produit) !== false)) {
				$contrats[] = $contrat;
            }
        }
        return $contrats;
    }

}