<?php
class CompteEmailView extends acCouchdbView
{
	
	const KEY_TYPE = 0;
	const KEY_EMAIL = 1;
	
	const VALUE_LOGIN = 0;
	const VALUE_ID = 1;

	public static function getInstance() 
	{
        return acCouchdbManager::getView('compte', 'email', '_Compte');
    }

    public function findAll() 
    {
    	return $this->client->getView($this->design, $this->view);
  	}
  	
  	public function findByEmail($email)
  	{
  		return $this->client->startkey(array('CompteTiers', $email))->endkey(array('CompteTiers', $email, array()))->getView($this->design, $this->view);
  	}
}