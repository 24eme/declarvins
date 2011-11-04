<?php

class tiersComponents extends sfComponents {
    /**
     *
     * @param sfWebRequest $request
     */
    public function executeChoixTiers(sfWebRequest $request) {
    	$this->compte = $this->getUser()->getCompte();
    	$this->hasMultiTiers = false;
		if (count($this->compte->tiers) != 1) {
			$this->hasMultiTiers = true;
			$this->form = new TiersLoginForm($this->compte);
			if ($tiers = $this->getUser()->getTiers()) {
				$this->form->getWidget('tiers')->setDefault($tiers->_id);
			}
		}
    }
    
}
