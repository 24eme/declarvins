<?php
class CielDrmView extends acCouchdbView
{
	const KEY_CIELTRANSFERT = 0;
	const KEY_ACCISES = 1;
	const KEY_PERIODE = 2;
	

	public static function getInstance() 
	{
        return acCouchdbManager::getView('ciel', 'drm', 'DRM');
    }

    public function findByAccisesPeriode($accises, $periode) 
    {
      	$result = $this->client->startkey(array(1, $accises, $periode))
                    		->endkey(array(1,$accises,$periode, array()))
                    		->reduce(false)
                    		->getView($this->design, $this->view);
      	foreach ($result->rows as $row) {
      		return DRMClient::getInstance()->find($row->id);
      	}
      	return null;
    }

}  