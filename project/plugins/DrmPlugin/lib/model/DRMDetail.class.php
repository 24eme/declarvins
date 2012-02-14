<?php

/**
 * Model for DRMDetail
 *
 */
class DRMDetail extends BaseDRMDetail {
    
    public function getConfig() {

        return $this->getMillesime()->getConfig();
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
    
    public function getLabelKey() {
    	$key = null;
    	if ($this->label) {
    		$key = implode('-', $this->label->toArray());
    	}
    	return ($key) ? $key : DRM::DEFAULT_KEY;
    }

    public function getLabelLibelle() {
        $libelles = array(); 
        foreach($this->label as $key) {
            $libelles[] = ConfigurationClient::getCurrent()->label[$key];
        }

        return implode(', ', $libelles);
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

}