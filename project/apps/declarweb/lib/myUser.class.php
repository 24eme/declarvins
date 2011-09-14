<?php

class myUser extends sfBasicSecurityUser
{
	/**
	 * 
	 * @return _Compte
	 */
	public function getCompte()
	{
		return sfCouchdbManager::getClient('_Compte')->retrieveByLogin('login');
	}
}
