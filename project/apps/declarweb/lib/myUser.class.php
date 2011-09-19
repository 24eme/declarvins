<?php

class myUser extends sfBasicSecurityUser
{
	/**
	 * Récupération du compte en statique (temporaire)
         * @todo Récupérer le compte qui est en session
	 * @return _Compte
	 */
	public function getCompte()
	{
            $contrat = sfCouchdbManager::getClient('Contrat')->retrieveById($this->getAttribute('contrat_id'));
            return sfCouchdbManager::getClient('_Compte')->getById($contrat->getCompte());
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
