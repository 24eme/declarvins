<?php

class myUser extends sfBasicSecurityUser
{
	/**
	 * Récupération du compte 
	 * @return _Compte
	 */
	public function getCompte()
	{
            return sfCouchdbManager::getClient('_Compte')->getById($this->getAttribute('compte_id'));
	}
	/**
	 * Récupération du contrat
	 * @return Contrat
	 */
	public function getContrat()
	{
            return sfCouchdbManager::getClient('Contrat')->retrieveDocumentById($this->getAttribute('contrat_id'));
	}
        /**
	 * Récupération de l'interpro en statique (temporaire)
         * @todo Récupérer l'interpro qui est en session
	 * @return Interpro
	 */
	public function getInterpro()
	{
            return sfCouchdbManager::getClient('Interpro')->getById($this->getAttribute('interpro_id'));
	}
}
