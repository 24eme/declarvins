<?php

abstract class TiersSecurityUser extends CompteSecurityUser {

    protected $_tiers = null;
    const SESSION_TIERS = 'tiers';
    const NAMESPACE_TIERS = 'TiersSecurityUser';
    const CREDENTIAL_TIERS = 'tiers';
    const CREDENTIAL_INTERPRO = 'interpro';
    const CREDENTIAL_ETABLISSEMENT = 'etablissement';
    
    const CREDENTIAL_DROIT_DRM = 'drm';

    protected $_credentials_tiers = array(
        self::CREDENTIAL_TIERS,
        self::CREDENTIAL_INTERPRO,
        self::CREDENTIAL_ETABLISSEMENT
        );
    
    protected $_credentials_droits = array(
        self::CREDENTIAL_DROIT_DRM
        );

    /**
     *
     * @param sfEventDispatcher $dispatcher
     * @param sfStorage $storage
     * @param type $options 
     */
    public function initialize(sfEventDispatcher $dispatcher, sfStorage $storage, $options = array()) {
        parent::initialize($dispatcher, $storage, $options);

        if (!$this->isAuthenticated()) {
            $this->signOutTiers();
        }
    }

    /**
     *
     * @param _Tiers $tiers 
     */
    public function signInTiers($tiers) {
        $this->requireCompte();
        $this->signOutTiers();
        $this->addCredential(self::CREDENTIAL_TIERS);
        if ($tiers->type == "Interpro") {
        	$this->addCredential(self::CREDENTIAL_INTERPRO);
        } elseif ($tiers->type == "Etablissement") {
        	$this->addCredential(self::CREDENTIAL_ETABLISSEMENT);
                $this->addCredential(self::CREDENTIAL_DROIT_DRM);
        }
        $this->setAttribute(self::SESSION_TIERS, $tiers->_id, self::NAMESPACE_TIERS);
    }

    /**
     * 
     */
    protected function clearCredentialsTiers() {
        foreach ($this->_credentials_tiers as $credential) {
            $this->removeCredential($credential);
        }
    }

    /**
     * 
     */
    public function signOutTiers() {
        $this->_tiers = null;
        $this->getAttributeHolder()->removeNamespace(self::NAMESPACE_TIERS);
        $this->clearCredentialsTiers();
    }

    /**
     * @return _Tiers
     */
    public function getTiers($type = null) {
        $this->requireTiers();
        if (is_null($this->_tiers)) {
            $this->_tiers = array();
            if ($this->getAttribute(self::SESSION_TIERS, null, self::NAMESPACE_TIERS)) {
            	$this->_tiers = sfCouchdbManager::getClient()->retrieveDocumentById($this->getAttribute(self::SESSION_TIERS, null, self::NAMESPACE_TIERS));
            } else {
                $this->_tiers = $this->getCompte()->getTiers();
            }
            if (!$this->_tiers) {
                $this->signOutCompte();
                throw new sfException("The tiers does not exist");
            }
        }
        return $this->_tiers;
    }

    /**
     * 
     */
    protected function requireTiers() {
        $this->requireCompte();
        if (!$this->hasCredential(self::CREDENTIAL_TIERS)) {
            throw new sfException("you must be logged in with a tiers");
        }
    }

    /**
     *
     * @param string $namespace 
     */
    public function signOutCompte($namespace) {
        $this->signOutTiers();
        parent::signOutCompte($namespace);
    }

}
