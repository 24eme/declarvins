<?php
/**
 * Model for DRMCertification
 */

class DRMAppellation extends BaseDRMAppellation {
    
    /**
     *
     * @return DRMCertification
     */
    public function getGenre() {

        return $this->getParentNode();
    }

    public function getChildrenNode() {

        return $this->mentions;
    }

     /**
     *
     * @return DRMGenre
     */
    public function getCertification() {
        return $this->getGenre()->getParent()->getParent();
    }
    
    public function updateDroits($droits) {
    	$mergeSorties = array();
    	$mergeEntrees = array();
    	if ($this->getDocument()->getInterpro()->getKey() == Interpro::INTERPRO_KEY.Interpro::INTER_RHONE_ID) {
    		$mergeSorties = DRMDroits::getDroitSortiesInterRhone();
    		$mergeEntrees = DRMDroits::getDroitEntreesInterRhone();
    	}
    	foreach ($this->getDroits() as $typedroits => $droit) {
    		$droits->add($typedroits)->add($droit->code)->integreVolume($this->sommeLignes(DRMDroits::getDroitSorties($mergeSorties)), $this->sommeLignes(DRMDroits::getDroitEntrees($mergeEntrees)), $droit->taux, $this->getReportByDroit($droit), $droit->libelle);
    	}
    }


    public function getReportByDroit($droit) {
    	$drmPrecedente = $this->getDocument()->getPrecedente();
    	if ($drmPrecedente && $drmPrecedente->isNew()) {
    		return 0;
    	} elseif($drmPrecedente) {
    		if ($drmPrecedente->get('droits')->get('douane')->exist($droit->code)) {
    			return $drmPrecedente->get('droits')->get('douane')->get($droit->code)->cumul;
    		} else {
    			return 0;
    		}
    	}
    }
    
    public function getDroit($type) {
      return $this->getConfig()->getDroits($this->getInterproKey())->get($type)->getCurrentDroit($this->getCampagne());
    }

    public function getDroits() {
      $conf = $this->getConfig();
      $droits = array();
      foreach ($conf->getDroits($this->getInterproKey()) as $key => $droit) {
	$droits[$key] = $droit->getCurrentDroit($this->getCampagne());
      }
      return $droits;
    }
    public function getInterproKey() {
      if (!$this->getDocument()->getInterpro())
	return array();
      return $this->getDocument()->getInterpro()->get('_id');
    }
    public function getCampagne() {
      return $this->getDocument()->getCampagne();
    }
    
    public function hasDetailLigne($ligne)
    {
    	if ($configurationDetail = $this->getConfig()->exist('detail')) {
    		$line = $configurationDetail->get($ligne);
    		if (!is_null($line->readable)) {
    			return $line->readable;
    		}
    	}
    	return $this->getGenre()->hasDetailLigne($ligne);
    }
}
