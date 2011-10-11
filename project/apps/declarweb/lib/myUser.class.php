<?php

class myUser extends sfBasicSecurityUser
{
	/**
	 * Récupération du compte 
	 * @return _Compte
	 */
	public function getCompte()
	{
		return ($this->hasAttribute('compte_id'))? sfCouchdbManager::getClient('_Compte')->getById($this->getAttribute('compte_id')) : null;
	}
	/**
	 * Récupération du contrat
	 * @return Contrat
	 */
	public function getContrat()
	{
		return ($this->hasAttribute('contrat_id'))? sfCouchdbManager::getClient('Contrat')->retrieveDocumentById($this->getAttribute('contrat_id')) : null;
	}
	/**
	 * Récupération de l'interpro
	 * @return Interpro
	 */
	public function getInterpro()
	{
		return ($this->hasAttribute('interpro_id'))? sfCouchdbManager::getClient('Interpro')->getById($this->getAttribute('interpro_id')) : null;
	}
}
