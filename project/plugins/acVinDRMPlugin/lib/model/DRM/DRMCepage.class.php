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

	public function getCertification() {

    	return $this->getCouleur()->getLieu()->getCertification();
  	}

	public function getGenre() {

    	return $this->getCouleur()->getLieu()->getGenre();
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

	public function isInao() {
		return preg_match('/^[0-9]/', $this->getInao());
	}

    public function getInao() {
		$inao = $this->_get('inao');
        $config = $this->getConfig();
    	if(!$config) {
    		throw new Exception("Le produit n'a pas été trouvé dans la configuration : DRM-".$this->getDocument()->getIdentifiant().'-'.$this->getDocument()->getPeriode().":".$this->getHash());
    	}
        if ($config->getInao() && !$inao) {
			$inao = $config->getInao();
			if (strlen($inao) == 5) {
				$inao = $inao.' ';
			}
			$this->setInao($inao);
		}
		if ($this->getDocument()->isNouvelleCampagne() && $this->_get('inao') != $config->getInao()) {
		    $this->setInao((strlen($config->getInao()) == 5)? $config->getInao().' ' : $config->getInao());
		}
		return $this->_get('inao');
	}

    public function getLibelleFiscal() {
		$lf = $this->_get('libelle_fiscal');
        $config = $this->getConfig();
    	if(!$config) {
    		throw new Exception("Le produit n'a pas été trouvé dans la configuration : DRM-".$this->getDocument()->getIdentifiant().'-'.$this->getDocument()->getPeriode().":".$this->getHash());
    	}
        if ($config->getLibelleFiscal() && !$lf) {
			$this->setLibelleFiscal($config->getLibelleFiscal());
		}
		if ($this->getDocument()->isNouvelleCampagne() && $this->_get('libelle_fiscal') != $config->getLibelleFiscal()) {
		    $this->setLibelleFiscal($config->getLibelleFiscal());
		}
		if(!$this->_get('libelle_fiscal') && $this->getDocument()->isNegoce()) {
			$this->setLibelleFiscal($this->devineLibelleFiscal());
		}

		return $this->_get('libelle_fiscal');
	}

	public function devineLibelleFiscal() {

        if(in_array($this->getGenre(), array("VDN", "N"))) {
            foreach($this->getDetails() as $detail) {
                $tavLF = ($detail->tav > 18) ? "SUP_18" : "INF_18";

                    return "VDN_VDL_AOP_".$tavLF;
                }
            }
        }

		$genreLF = preg_match("/(EFF)/", $this->getGenre()->getKey()) ? "M" : "T";

		if(preg_match("/(AOC|AOP)/", $this->getCertification()->getKey())) {

			return "V".$genreLF."_IG_AOP";
		}

		if(preg_match("/IGP/", $this->getCertification()->getKey())) {

			return "V".$genreLF."_IG_IGP";
		}

		if(preg_match("/(VINSSANSIG)/", $this->getCertification()->getKey()) && !in_array($this->getKey(),array("CEP", "DEFAUT", "SANSCEP", "SANS"))) {

			return "V".$genreLF."_SANS_IG_CEPAGES";
		} elseif(preg_match("/(VINSSANSIG)/", $this->getCertification()->getKey())) {

            return "V".$genreLF."_SANS_IG_AUTRES";
        }

		return null;
	}


}
