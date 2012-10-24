<?php

class AlerteClient extends acCouchdbClient {
   
    /**
     *
     * @return AlerteClient
     */
    public static function getInstance()
    {
      return acCouchdbManager::getClient("Alerte");
    }
    
	public function findAllByOptions($interpro, $type, $options) {
    	return $this->client->startkey(array($interpro, Alerte::STATUT_ACTIF, $type))
                    		->endkey(array($interpro, Alerte::STATUT_ACTIF, $type, array()))
                    		->getView($this->design, $this->view);
    }

    
 }
