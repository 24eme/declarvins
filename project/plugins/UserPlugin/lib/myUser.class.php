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
	/**
	 * Récupération de l'interpro
	 * @return Interpro
	 */
	public function getInterpro()
	{
		return ($this->hasAttribute('interpro_id'))? acCouchdbManager::getClient('Interpro')->find($this->getAttribute('interpro_id')) : null;
	}
}
