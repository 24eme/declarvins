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

  	public function getProduits($interpro = null) {
        $produits = array();
        if ($interpro && !is_array($interpro)) {
        	$interpro = array($interpro);
        }
        foreach($this->getChildrenNode() as $key => $item) {
        	if ($interpro && !in_array($item->interpro, $interpro)) {
        		continue;
        	}
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
  
    public function getInao() {
		$inao = $this->_get('inao');
        if ($inao != $this->getConfig()->getInao()) {
			$inao = $this->getConfig()->getInao();
			if (strlen($inao) == 5) {
				$inao = $inao.' ';
			}
			$this->setInao($inao);
		}
		return $inao;
	}
	
  	protected function update($params = array()) {
  		parent::update($params);
  		$configuration = ConfigurationClient::getCurrent();
	  	if (!$this->inao) {
	  		$inao = $this->getConfig()->getInao();
	  		if (strlen($inao) == 5) {
	  			$inao = $inao.' ';
	  		}
	  		$this->inao = $inao;
	  	}
	  	if (!$this->libelle_fiscal) {
	  		$this->libelle_fiscal = $this->getConfig()->getLibelleFiscal();
	  	}
  		
  	}

}
