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

    
 }
