<?php

class _CompteClient extends acVinCompteClient 
{        
    /**
     *
     * @param string $id
     * @param integer $hydrate
     * @return _Compte
     */
    public function getById($id, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) 
    {
        return parent::retrieveDocumentById($id, $hydrate);
    }
}
