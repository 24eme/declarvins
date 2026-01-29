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
        if ($millesime && $this->exist('reserve_interpro_details')) {
            return ($this->_get('reserve_interpro_details')->exist($millesime))? $this->_get('reserve_interpro_details')->get($millesime) : 0;
        }
        return ($this->hasReserveInterpro())? $this->_get('reserve_interpro') : 0;
    }

    public function getVolumeCommercialisable()
    {
        return $this->total - $this->getReserveInterpro();
    }

    public function setReserveInterpro($volume, $millesime = null)
    {
        $millesime = $millesime ?: $this->getMillesimeCourant();
        $reserveDetails = $this->getOrAdd('reserve_interpro_details');
        $reserveDetails->add($millesime, round($volume, 5));
        $this->updateVolumeReserveInterpro();
    }

    public function getReserveInterproMillesimes() {
        $millesimes = [];
        foreach ($this->getReserveInterproDetails() as $millesime => $ignore) {
            $millesimes[$millesime] = $millesime;
        }
        if ($this->exist('reserve_interpro_capacite_commercialisation_details')) {
            foreach ($this->get('reserve_interpro_capacite_commercialisation_details') as $millesime => $ignore) {
                $millesimes[$millesime] = $millesime;
            }
        }
        return array_keys($millesimes);
    }

    public function cleanReserveInterproDetails()
    {
        if ($this->exist('reserve_interpro_details')) {
            $clean = [];
            foreach ($this->_get('reserve_interpro_details') as $millesime => $volume) {
                if (!$volume) {
                    $clean[] = $millesime;
                }
            }
            foreach($clean as $m) {
                $this->_get('reserve_interpro_details')->remove($m);
                if ($this->exist('reserve_interpro_capacite_commercialisation_details') && $this->get('reserve_interpro_capacite_commercialisation_details')->exist($m)) {
                    $this->get('reserve_interpro_capacite_commercialisation_details')->remove($m);
                }
                if ($this->exist('reserve_interpro_suivi_sorties_chais_details') && $this->get('reserve_interpro_suivi_sorties_chais_details')->exist($m)) {
                    $this->get('reserve_interpro_suivi_sorties_chais_details')->remove($m);
                }
            }
            if (!count($this->_get('reserve_interpro_details'))) {
                $this->remove('reserve_interpro_details');
            }
            if ($this->exist('reserve_interpro_capacite_commercialisation_details') && !count($this->reserve_interpro_capacite_commercialisation_details)) {
                $this->remove('reserve_interpro_capacite_commercialisation_details');
                $this->remove('reserve_interpro_capacite_commercialisation');
            }
            if ($this->exist('reserve_interpro_suivi_sorties_chais_details') && !count($this->reserve_interpro_suivi_sorties_chais_details)) {
                $this->remove('reserve_interpro_suivi_sorties_chais_details');
                $this->remove('reserve_interpro_suivi_sorties_chais');
            }
        }
    }

    public function getReserveInterproDetails() {
        $this->cleanReserveInterproDetails();
        if ($this->exist('reserve_interpro_details')) {
            return $this->_get('reserve_interpro_details');
        }
        return ['' => ($this->exist('reserve_interpro')) ? $this->_get('reserve_interpro') : 0];
    }

    public function updateVolumeReserveInterpro()
    {
        $volumeTotalEnReserve = 0;
        foreach ($this->getReserveInterproDetails() as $millesime => $volume) {
            $volumeTotalEnReserve += $volume;
        }
        if ($volumeTotalEnReserve > 0) {
            $this->getOrAdd('reserve_interpro');
            $this->_set('reserve_interpro', round($volumeTotalEnReserve, 5));
        }
    }

    public function hasReserveInterproMultiMillesime()
    {
        return ($this->exist('reserve_interpro_details') && count($this->_get('reserve_interpro_details')) > 1);
    }

    public function hasCapaciteCommercialisation()
    {
        return (($this->exist('reserve_interpro_capacite_commercialisation') && $this->reserve_interpro_capacite_commercialisation > 0)||($this->exist('reserve_interpro_capacite_commercialisation_details') && count($this->reserve_interpro_capacite_commercialisation_details) > 0));
    }

    public function checkReserveInterproDetails() {
        foreach($this->getReserveInterproDetails() as $millesime => $ignore) {
            if ($this->hasCapaciteCommercialisation()) {
                if (!$this->exist('reserve_interpro_capacite_commercialisation_details')) {
                    $this->add('reserve_interpro_capacite_commercialisation_details');
                }
                if (!$this->reserve_interpro_capacite_commercialisation_details->exist($millesime)) {
                    $this->reserve_interpro_capacite_commercialisation_details->add($millesime, $this->get('reserve_interpro_capacite_commercialisation'));
                }
            }
            if ($this->hasSuiviSortiesChais()) {
                if (!$this->exist('reserve_interpro_suivi_sorties_chais_details')) {
                    $this->add('reserve_interpro_suivi_sorties_chais_details');
                }
                if (!$this->reserve_interpro_suivi_sorties_chais_details->exist($millesime)) {
                    $this->reserve_interpro_suivi_sorties_chais_details->add($millesime, $this->get('reserve_interpro_suivi_sorties_chais'));
                }
            }
        }
    }

    public function getCapaciteCommercialisation($millesime)
    {
        if (!$this->hasCapaciteCommercialisation()) {
            return null;
        }
        $this->checkReserveInterproDetails();
        if ($this->exist('reserve_interpro_capacite_commercialisation_details') && $this->reserve_interpro_capacite_commercialisation_details->exist($millesime)) {
            return $this->reserve_interpro_capacite_commercialisation_details->get($millesime);
        }
        return ($this->exist('reserve_interpro_capacite_commercialisation'))? $this->get('reserve_interpro_capacite_commercialisation') : null;
    }

    public function setCapaciteCommercialisation($volume, $millesime = null)
    {
        if ($millesime) {
            $reserveDetails = $this->getOrAdd('reserve_interpro_capacite_commercialisation_details');
            return $this->reserve_interpro_capacite_commercialisation_details->add($millesime, round($volume, 5));
        }
        $this->add('reserve_interpro_capacite_commercialisation', round($volume, 5));
    }

    public function hasSuiviSortiesChais()
    {
        return (($this->exist('reserve_interpro_suivi_sorties_chais') && $this->reserve_interpro_suivi_sorties_chais > 0)||($this->exist('reserve_interpro_suivi_sorties_chais_details') && count($this->reserve_interpro_suivi_sorties_chais_details) > 0));
    }

    public function getSuiviSortiesChais($millesime = null)
    {
        if (!$this->hasSuiviSortiesChais()) {
            return null;
        }
        $this->checkReserveInterproDetails();
        if ($this->exist('reserve_interpro_suivi_sorties_chais_details') && $this->reserve_interpro_suivi_sorties_chais_details->exist($millesime)) {
            return $this->reserve_interpro_suivi_sorties_chais_details->get($millesime);
        }
        return ($this->exist('reserve_interpro_suivi_sorties_chais'))? $this->get('reserve_interpro_suivi_sorties_chais') : null;
    }

    public function setSuiviSortiesChais($volume, $millesime = null)
    {
        if ($millesime) {
            $reserveDetails = $this->getOrAdd('reserve_interpro_suivi_sorties_chais_details');
            return $this->reserve_interpro_suivi_sorties_chais_details->add($millesime, round($volume, 5));
        }
        $this->add('reserve_interpro_suivi_sorties_chais', round($volume, 5));
    }

    public function getReserveInterproPeriode($millesime, $format = 'Y-m-d') {
        if (!$millesime) {
            return [];
        }
        $mois = DRMConfiguration::getInstance()->getReserveInterproParamValue($this->getHash(), 'debut_mois');
        $duree = DRMConfiguration::getInstance()->getReserveInterproParamValue($this->getHash(), 'duree_mois');
        $debut = new DateTimeImmutable(sprintf('%d-%02d-%02d', $millesime, $mois, 1));
        $fin = $debut->modify(sprintf('%+d months', $duree))->modify('last day of this month');
        return [$debut->format($format), $fin->format($format)];
    }

    public function reserveInterproExpiree($millesime) {
        if (!$millesime) {
            return false;
        }
        $drm_date = $this->getDocument()->getPeriode().'-01';
        $periode = $this->getReserveInterproPeriode($millesime);
        if ($drm_date > $periode[1]) {
            return true;
        }
        return false;
    }

    public function isInReserveInterproPeriode($millesime) {
        if (!$millesime) {
            return null;
        }
        $drm_date = $this->getDocument()->getPeriode().'-01';
        $periode = $this->getReserveInterproPeriode($millesime);
        if ($drm_date < $periode[0]) {
            return false;
        }
        if ($drm_date > $periode[1]) {
            return false;
        }
        return true;
    }

    public function updateSuiviSortiesChais($millesime)
    {
        if ($this->hasCapaciteCommercialisation()) {
            if ($this->isInReserveInterproPeriode($millesime)) {
                $periodeReserve = $this->getReserveInterproPeriode($millesime);
                $periodeDrm = $this->getDocument()->getPeriode().'-01';
                $volumeSortieChai = $this->getVolumeSortieChai();
                if ($periodeReserve && $periodeDrm > $periodeReserve[0] && $periodeDrm <= $periodeReserve[1]) {
                    $drmPrecedente = $this->getDocument()->getPrecedente();
                    if ($drmPrecedente && !$drmPrecedente->isNew() && $drmPrecedente->exist($this->getHash())) {
                        $volumeSortieChai += $drmPrecedente->get($this->getHash())->getSuiviSortiesChais($millesime);
                    }
                }
                $this->setSuiviSortiesChais(round($volumeSortieChai, 2), $millesime);
            }
        } else {
            if ($this->exist('reserve_interpro_suivi_sorties_chais')) {
                $this->remove('reserve_interpro_suivi_sorties_chais');
            }
            if ($this->exist('reserve_interpro_suivi_sorties_chais_details')) {
                $this->remove('reserve_interpro_suivi_sorties_chais_details');
            }
        }
    }

    public function updateAutoReserveInterpro()
    {
        foreach($this->getReserveInterproMillesimes() as $millesime) {
            if ($this->reserveInterproExpiree($millesime)) {
                $this->setReserveInterpro(0, $millesime);
            } elseif ($this->isInReserveInterproPeriode($millesime)) {
                $this->updateSuiviSortiesChais($millesime);
                $capacite = $this->getCapaciteCommercialisation($millesime);
                $cumul = $this->getSuiviSortiesChais($millesime);
                if ($capacite > 0 && $cumul > $capacite) {
                    $volumeSortieChai = $this->getVolumeSortieChai();
                    $diff = $cumul - $capacite;
                    $deduction = ($diff < $volumeSortieChai)? $diff : $volumeSortieChai;
                    $reserve = $this->getReserveInterpro($millesime) - $deduction;
                    if ($reserve < 0) {
                        $reserve = 0;
                    }
                    $this->setReserveInterpro($reserve, $millesime);
                }
            } else {
                $this->setSuiviSortiesChais(0, $millesime);
            }
        }
    }

}
