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

	public function getLieu() {

    	return $this->getCouleur()->getLieu();
  	}

	public function getAppellation() {

    	return $this->getCouleur()->getLieu()->getAppellation();
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
        if(in_array($this->getGenre()->getKey(), array("VDN", "N"))) {
            foreach($this->getDetails() as $detail) {
                $tavLF = ($detail->tav > 18) ? "SUP_18" : "INF_18";

                return "VDN_VDL_AOP_".$tavLF;
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

			return "V".$genreLF."_SANS_IG_CEPAGE";
		} elseif(preg_match("/(VINSSANSIG)/", $this->getCertification()->getKey())) {

            return "V".$genreLF."_SANS_IG_AUTRES";
        }

		return null;
	}

    public function getIdentifiantHTML() {
        return strtolower(str_replace($this->getDocument()->declaration->getHash(), '', str_replace('/', '_', preg_replace('|\/[^\/]+\/DEFAUT|', '', $this->getHash()))));
    }

    public function makeFormattedLibelle($format = "%g% %a% %l% %co% %ce%", $label_separator = ", ") {
        return ConfigurationProduitClient::getInstance()->format($this->getConfig()->getLibelles(), array(), $format);
    }

    public function getInterpro() {
        if ($config = $this->getConfig()) {
            return $config->getDocument()->interpro;
        } else {
            return null;
        }
    }

    public function getVolumeSortieChai() {
        $volume = 0;
        foreach($this->getDetails() as $detail) {
            $volume += $detail->getVolumeSortieChai();
        }
        return round($volume, 5);
    }

    /*
        RESERVE INTERPRO
    */


    public function getMillesimeCourant()
    {
        return substr($this->getDocument()->campagne, 0, 4);
    }

    public function hasReserveInterpro()
    {
        return $this->exist('reserve_interpro');
    }

    public function getReserveInterpro($millesime = null)
    {
        if ($millesime) {
            return $this->reserve_interpro_details->getOrAdd($millesime) ?: 0;
        }
        if ($this->hasReserveInterpro()) {
            return $this->_get('reserve_interpro');
        }
        return 0;
    }

    public function getVolumeCommercialisable()
    {
        return $this->total - $this->getReserveInterpro();
    }

    public function setReserveInterpro($volume, $millesime = null)
    {
        $millesime = $millesime ?: $this->getMillesimeCourant();
        $reserveDetails = $this->getOrAdd('reserve_interpro_details');
        $this->reserve_interpro_details->add($millesime, round($volume, 5));
        $this->updateVolumeReserveInterpro();
    }

    public function getReserveInterproDetails() {

        if ($this->exist('reserve_interpro_details')) {
            return $this->_get('reserve_interpro_details');
        }
        return ['' => $this->_get('reserve_interpro')];
    }

    public function updateVolumeReserveInterpro()
    {
        $volumeTotalEnReserve = 0;
        foreach ($this->getOrAdd('reserve_interpro_details') as $millesime => $volume) {
            $volumeTotalEnReserve += $volume;
        }
        $this->getOrAdd('reserve_interpro');
        if ($volumeTotalEnReserve < 0) {
            $volumeTotalEnReserve = 0;
        }
        $this->_set('reserve_interpro', round($volumeTotalEnReserve, 5));
    }

    public function hasReserveInterproMultiMillesime()
    {
        return (count($this->getOrAdd('reserve_interpro_details')) > 1);
    }

    public function getMillesimeForReserveInterpro() {
        $details = $this->getOrAdd('reserve_interpro_details')->toArray(true,false);
        if (!count($details)) {
            return null;
        }
        krsort($details);
        return array_key_first($details);
    }

    public function hasCapaciteCommercialisation()
    {
        return $this->exist('reserve_interpro_capacite_commercialisation');
    }

    public function getCapaciteCommercialisation($millesime)
    {
        if (!$this->hasCapaciteCommercialisation()) {
            return 0;
        }
        if ($this->exist('reserve_interpro_capacite_commercialisation_details') && $this->reserve_interpro_capacite_commercialisation_details->exist($millesime)) {
            return $this->reserve_interpro_capacite_commercialisation_details->get($millesime);
        }
        return $this->_get('reserve_interpro_capacite_commercialisation');
    }

    public function setCapaciteCommercialisation($volume, $millesime = '')
    {
        if ($millesime) {
            $reserveDetails = $this->getOrAdd('reserve_interpro_capacite_commercialisation_details');
            return $this->reserve_interpro_capacite_commercialisation_details->add($millesime, round($volume, 5));
        }
        $this->_set('reserve_interpro_capacite_commercialisation', $volume);
    }

    public function hasSuiviSortiesChais()
    {
        return $this->exist('reserve_interpro_suivi_sorties_chais');
    }

    public function getSuiviSortiesChais($millesime)
    {
        if (!$this->hasSuiviSortiesChais()) {
            return ;
        }
        if ($this->exist('reserve_interpro_suivi_sorties_chais_details') && $this->reserve_interpro_suivi_sorties_chais_details->exist($millesime)) {
            return $this->reserve_interpro_suivi_sorties_chais_details->get($millesime);
        }
        return $this->_get('reserve_interpro_suivi_sorties_chais');
    }

    public function setSuiviSortiesChais($volume, $millesime = '')
    {
        if ($millesime) {
            $reserveDetails = $this->getOrAdd('reserve_interpro_suivi_sorties_chais_details');
            return $this->reserve_interpro_suivi_sorties_chais_details->add($millesime, round($volume, 5));
        }
        $this->_set('reserve_interpro_suivi_sorties_chais', $volume);
    }


    public function getReserveInterproPeriode($millesime, $format = '%Y-%m-%d') {
        if (!$millesime) {
            return array();
        }
        setlocale(LC_ALL, 'fr_FR.UTF8', 'fr_FR','fr','fr','fra','fr_FR@euro');
        $debut_time = strtotime($millesime.'-12-31');
        $debut = strftime($format, $debut_time);
        $fin = strftime($format, strtotime('last day of +18 months', $debut_time));
        return [$debut, $fin];
    }

    public function updateSuiviSortiesChais()
    {
        if ($this->hasCapaciteCommercialisation()) {
            $vol = round($this->getSuiviSortiesChais() + $this->getVolumeSortieChai(), 2);
            $this->add('reserve_interpro_suivi_sorties_chais', $vol);
        }
    }

    public function updateAutoReserveInterpro()
    {
        if (!$this->hasCapaciteCommercialisation()||!$this->hasSuiviSortiesChais()) {
            return;
        }
        if ($this->getAppellation()->getKey() == 'RTA') {
            return;
        }
        $capacite = $this->getCapaciteCommercialisation();
        $cumul = $this->getSuiviSortiesChais();
        if ($capacite > 0 && $cumul > $capacite) {
            $millesime = $this->getMillesimeForReserveInterpro();
            $reserve = $this->getReserveInterpro($millesime) - ($cumul - $capacite);
            if ($reserve < 0) {
                $reserve = 0;
            }
            $this->setReserveInterpro($reserve, $millesime);
            $this->updateVolumeReserveInterpro();
        }
    }

}
