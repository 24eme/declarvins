<?php

abstract class DrmSecurityUser extends TiersSecurityUser {

    const CREDENTIAL_DRM_EN_COURS = 'drm_en_cours';
    const CREDENTIAL_DRM_VALIDE = 'drm_valide';
    
    protected $_credentials_drm = array(self::CREDENTIAL_DRM_EN_COURS, 
                                        self::CREDENTIAL_DRM_VALIDE);
    
    protected $_drm = null;
    
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
            $this->signOutDrm();
        }
    }
    
    /**
    * 
    */
    protected function clearCredentialsDrm() {
        foreach($this->_credentials_drm as $credential) {
            $this->removeCredential($credential);
        }
    }
    
    /**
     * 
     */
    public function signOutDrm() {
        $this->_drm = null;
        $this->clearCredentialsDrm();
    }
    
    /**
     * @return DR
     */
    public function getDrm() {
        $this->requireDrm();
        $this->requireTiers();
        if (is_null($this->_drm)) {
            $this->_drm = $this->getTiers()->getDrm($this->getCampagne());
            if (!$this->_drm) {
                $drm = new DRM();
                $drm->identifiant = $this->getTiers()->identifiant;
                $drm->campagne = $this->getCampagne();
                return $drm;
            }
        }

        return $this->_drm;
    }
    
    /**
     * @return string
     */
    public function getCampagneDrm() {
      return CurrentClient::getCurrent()->campagne;
    }
    /**
     * returns true if editable
     */
    public function isDrmEditable() {
    	return true;
    }

    /**
     * 
     */
    public function initCredentialsDrm() {
        $this->requireDrm();
        $drm = $this->getDrm();
        $this->clearCredentialsDrm();
        /*if ($this->isDrmEditable()) {
            if ($drm->isValideeTiers() || $drm->isValideeCiva()) {
                $this->addCredential(self::CREDENTIAL_DECLARATION_VALIDE);
            } else {
                $this->addCredential(self::CREDENTIAL_DECLARATION_EN_COURS);
                $this->addCredentialsEtapeDrm();
            }
        }*/
    }
    
    /**
     * 
     */
    protected function requireDrm() {
        $this->requireTiers();
        if (!$this->hasCredential(self::CREDENTIAL_DROIT_DRM)) {
            throw new sfException("you must be logged in with a tiers");
        }
    }
    
    /**
     *
     * @param _Tiers $tiers 
     */
    public function signInTiers($tiers) {
        parent::signInTiers($tiers);
        if($this->hasCredential(TiersSecurityUser::CREDENTIAL_DROIT_DRM)) {
            $this->initCredentialsDrm();
        }
    }

    /**
     *
     * @param string $namespace 
     */
    public function signOutCompte($namespace) {
        $this->signOutDrm();
        parent::signOutCompte($namespace);
    }

    /**
     * 
     */
    public function signOutTiers() {
        $this->signOutDrm();
        parent::signOutTiers();
    }
    
    public function removeDrm() {
    	$this->getDrm()->delete();
        $this->signOutDrm();
        $this->initCredentialsDrm();
    }
}
