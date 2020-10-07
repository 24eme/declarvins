<?php

class VracDeterminationprixView extends acCouchdbView
{
	const VRAC_VIEW_DATE = 0;
    
	public static function getInstance() {

        return acCouchdbManager::getView('vrac', 'determinationprix', 'Vrac');
    }

	public function findLast($from = null) {
		$date_fin = date('Y-m-d');
		$date_debut = ($from && preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $from))? $from : date('Y-m-d', mktime(0, 0, 0, date("m"), date("d"), date("Y")-1));
        return $this->client->startkey(array($date_debut))
                    		->endkey(array($date_fin, array()))
                            ->getView($this->design, $this->view);
    }
    
}  