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
     * 
     */
    public function initCredentialsDRM() {
        $this->requireDRM();
        $this->clearCredentialsDRM();
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
}
