<?php

abstract class DRMSecurityUser extends TiersSecurityUser {

    const CREDENTIAL_DRM_EN_COURS = 'drm_en_cours';
    const CREDENTIAL_DRM_VALIDE = 'drm_valide';
    
    protected $_credentials_drm = array(self::CREDENTIAL_DRM_EN_COURS, 
                                        self::CREDENTIAL_DRM_VALIDE);
    
    protected $_drm = null;
    protected $_historique = null;
    
    /**
     *
     * @param sfEventDispatcher $dispatcher
     * @param sfStorage $storage
     * @param type $options 
     */
    public function initialize(sfEventDispatcher $dispatcher, sfStorage $storage, $options = array()) {
        parent::initialize($dispatcher, $storage, $options);
        
        if (!$this->isAuthenticated())
        {
            $this->signOutDRM();
        }
    }
    
    /**
    * 
    */
    protected function clearCredentialsDRM() {
        foreach($this->_credentials_drm as $credential) {
            $this->removeCredential($credential);
        }
    }
    
    /**
     * 
     */
    public function signOutDRM() {
        $this->_drm = null;
        $this->clearCredentialsDRM();
    }
    
    /**
     * @return DR
     */
    public function getDRM() {
    	$this->requireDRM();
    	$this->requireTiers();
    	if (is_null($this->_drm)) {
    		$lastDRM = $this->getDRMHistorique()->getLastDRM();

    		if ($lastDRM && $drm = DRMClient::getInstance()->find(key($lastDRM))) {
    			if (!$drm->isValidee()) {
    				$this->_drm = $drm;
    			} else {
    				$this->_drm = $drm->generateSuivante($this->getCampagneDRM());
    			}
    		} else {
    			$this->_drm = new DRM();
    			$this->_drm->identifiant = $this->getTiers()->identifiant;
    			$this->_drm->campagne = $this->getCampagneDRM();
    		}
    	}
    	return $this->_drm;
    }
    
    /**
     * @return DR
     */
    public function createDRMByCampagne($campagne = null) {
    	if (!$campagne) {
    		$campagne = date('Y-m');
    	}
    	$campagneTab = explode('-', $campagne);
    	$date_campagne = new DateTime($campagneTab[0].'-'.$campagneTab[1].'-01');
       	$date_campagne->modify('-1 month');
       	$prev_campagne = DRMClient::getInstance()->getCampagne($date_campagne->format('Y'), $date_campagne->format('m'));
       	$prev_drm = DRMClient::getInstance()->findLastByIdentifiantAndCampagne($this->getTiers()->identifiant, $prev_campagne);
       	if ($prev_drm) {
           $this->_drm = $prev_drm->generateSuivante($campagne);
       	} else {
       		$lastDRM = $this->getDRMHistorique()->getLastDRM();
    		if ($lastDRM && $drm = DRMClient::getInstance()->find(key($lastDRM))) {
    			$this->_drm = $drm->generateSuivante($campagne, false);
    		} else {
		    	$this->_drm = new DRM();
		    	$this->_drm->identifiant = $this->getTiers()->identifiant;
		    	$this->_drm->campagne = $campagne;
    		}
       	}
        return $this->_drm;
    }
    /**
     * @return DR
     */
    public function getLastDRMValide() {
        $lastDRM = $this->getDRMHistorique()->getLastDRM();
        if ($lastDRM && $drm = DRMClient::getInstance()->find(key($lastDRM))) {
	  if ($drm->isValidee()) {
	    return $drm;
	  }
        }
        return null;
    }
    
    /**
     * 
     */
    public function getDRMHistorique() {
    	if (is_null($this->_historique)) {
        	$this->_historique = new DRMHistorique($this->getTiers()->identifiant);
    	}
    	return $this->_historique;
    }
    
    public function hasDrmEnCours() 
    {
    	$historique = $this->getDRMHistorique();
    	$drms = $historique->getDRMs();
    	$hasDrmEnCours = false;
    	foreach ($drms as $drm) {
    		if (!($drm[DRMHistorique::VIEW_INDEX_STATUS] > 0)) {
    			$hasDrmEnCours = true;
    			break;
    		}
    	}
    	return $hasDrmEnCours;
    }
    
    /**
     * @return string
     */
    public function getCampagneDRM() {
      return CurrentClient::getCurrent()->campagne;
    }
    /**
     * returns true if editable
     */
    public function isDRMEditable() {
    	return true;
    }

    /**
     * 
     */
    public function initCredentialsDRM() {
        $this->requireDRM();
        $drm = $this->getDRM();
        $this->clearCredentialsDRM();
        /*if ($this->isDRMEditable()) {
            if ($drm->isValideeTiers() || $drm->isValideeCiva()) {
                $this->addCredential(self::CREDENTIAL_DECLARATION_VALIDE);
            } else {
                $this->addCredential(self::CREDENTIAL_DECLARATION_EN_COURS);
                $this->addCredentialsEtapeDRM();
            }
        }*/
    }
    
    /**
     * 
     */
    protected function requireDRM() {
        $this->requireTiers();
        if (!$this->hasCredential(self::CREDENTIAL_DROIT_DRM)) {
            throw new sfException("you must be logged in with a tiers");
        }
    }
    
    /**
     *
     * @param Etablissement $tiers 
     */
    public function signInTiers($tiers) {
        parent::signInTiers($tiers);
        if($this->hasCredential(TiersSecurityUser::CREDENTIAL_DROIT_DRM)) {
            $this->initCredentialsDRM();
        }
    }

    /**
     *
     * @param string $namespace 
     */
    public function signOutCompte($namespace) {
        $this->signOutDRM();
        parent::signOutCompte($namespace);
    }

    /**
     * 
     */
    public function signOutTiers() {
        $this->signOutDRM();
        parent::signOutTiers();
    }
    
    public function removeDRM() {
    	$this->getDRM()->delete();
        $this->signOutDRM();
        $this->initCredentialsDRM();
    }
}
