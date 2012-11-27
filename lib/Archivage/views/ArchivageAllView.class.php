<?php

class ArchivageAllView extends acCouchdbView
{
    
    const KEYS_TYPE = 0;
    const KEYS_CAMPAGNE = 1;
    const KEYS_NUMERO_ARCHIVE = 2;

    public static function getInstance() {

        return acCouchdbManager::getView('archivage', 'all');
    }
    
    public function getLastNumeroArchiveByTypeAndCampagne($type, $campagne) {  
        $rows = $this->client
            ->startkey(array($type, $campagne))
            ->endkey(array($type, $campagne, array()))   
            ->reduce(true)
            ->group_level(self::KEYS_CAMPAGNE)      
            ->getView($this->design, $this->view)->rows;

        $nb_docs = 0;

        foreach($rows as $row) {
            $nb_docs = $row->value;
        }

        if($nb_docs == 0) {

            return 0;  
        }

        $rows = $this->client
            ->startkey(array($type, $campagne))
            ->endkey(array($type, $campagne, array()))
            ->reduce(false)
            ->skip($nb_docs - 1)
            ->limit(1)
            ->getView($this->design, $this->view)->rows;

        foreach($rows as $row) {

            return $row->key[self::KEYS_NUMERO_ARCHIVE];
        }

        return 0;
    }
    
}  