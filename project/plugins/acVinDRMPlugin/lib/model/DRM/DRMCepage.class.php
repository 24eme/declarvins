<?php
/**
 * Model for DRMCepage
 *
 */

class DRMCepage extends BaseDRMCepage {
	
	/**
     *
     * @return DRMCouleur
     */
  	public function getCouleur() {
   
    	return $this->getParentNode();
  	}

  	public function getProduits() {
        $produits = array();
        foreach($this->getChildrenNode() as $key => $item) {
            $produits[$item->getHash()] = $item;
        }
        return $produits;
    }

  	public function getProduitsCepages() {
        return array($this->getHash() => $this);
    }

  	public function getLieuxArray() {

  		throw new sfException('this function need to call before lieu tree');
  	}

    public function getDetailsArray() {
      	$details = array();
      	foreach($this->details as $detail) {
        	$details[$detail->getHash()] = $detail;
      	}
      	
      	return $details;
    }

  	public function getChildrenNode() {

    	return $this->details;
  	}
  	
  	public function getTotalVolume($hashes) {
  		$total = null;
  		foreach ($this->getProduits() as $produit) {
  			foreach ($hashes as $hash) {
  				if ($produit->exist($hash)) {
  					$total += $produit->getOrAdd($hash);
  				} else {
  					$total += null;
  				}
  			}
  		}
  		return $total;
  	}
  	
  	public function getTav() {
  		foreach ($this->getProduits() as $produit) {
  			return $produit->tav;
  		}
  	}
  	
  	public function getPremix() {
  		foreach ($this->getProduits() as $produit) {
  			return $produit->premix;
  		}
  	}
  	
  	public function getObservation() {
  		foreach ($this->getProduits() as $produit) {
  			return $produit->observations;
  		}
  	}
  	
  	public function getHasSaisieAcq() {
  		$has = false;
  		foreach ($this->getProduits() as $produit) {
  			if ($produit->acq_total_debut_mois || $produit->acq_total_entrees || $produit->acq_total_sorties) {
  				$has = true;
  			}
  		}
  		return $has;
  	}
  	
  	protected function update($params = array()) {
  		parent::update($params);
  		$configuration = ConfigurationClient::getCurrent();
	  	if (!$this->inao) {
	  		$this->inao = $this->getConfig()->getInao();
	  	}
	  	if (!$this->libelle_fiscal) {
	  		$this->libelle_fiscal = $this->getConfig()->getLibelleFiscal();
	  	}
  		
  	}

}