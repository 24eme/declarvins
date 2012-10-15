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
	
}
