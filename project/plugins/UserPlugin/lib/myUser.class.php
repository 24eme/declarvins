<?php

class myUser extends acVinCompteSecurityUser
{
	/**
	 * Récupération du contrat
	 * @return Contrat
	 */
	public function getContrat()
	{
		return ($this->hasAttribute('contrat_id'))? acCouchdbManager::getClient('Contrat')->retrieveDocumentById($this->getAttribute('contrat_id')) : null;
	}

	public function getDerniereDrmSaisie()
	{
		$drm = $this->getAttribute('last_drm');
		return ($drm)? $drm : null;
	}

	public function isUsurpationMode() {

		return $this->getAttribute('initial_user');
	}

	public function hasTeledeclaration() {
	    return !$this->hasCredential(self::CREDENTIAL_OPERATEUR);
	}

    public function isAdmin()
    {
    	return $this->hasCredential(self::CREDENTIAL_ADMIN);
    }

}
