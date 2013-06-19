<?php
class OIOCAllView extends acCouchdbView
{
	
	const KEY_ID = 0;
	const KEY_STATUT = 1;
	const VALUE_ID = 0;
	const VALUE_IDENTIFIANT = 1;
	const VALUE_NOM = 2;

	public static function getInstance() 
	{
        return acCouchdbManager::getView('oioc', 'all', 'OIOC');
    }

    public function findAll() 
    {
    	return $this->client->getView($this->design, $this->view);
  	}
  	
  	public function getAllOIOC()
  	{
  		$oiocs = $this->findAll();
  		$result = array();
  		foreach ($oiocs->rows as $oioc) {
  			$result[$oioc->value[self::VALUE_ID]] = $oioc->value[self::VALUE_NOM];
  		}
  		return $result;
  	}
}