<?php

class ArchivageAllView extends acCouchdbView
{
    
    const KEYS_TYPE = 0;
    const KEYS_DATE = 1;
    const KEYS_NUMERO_ARCHIVE = 2;

    public static function getInstance() {

        return acCouchdbManager::getView('archivage', 'all');
    }
    
    public function getLastNumeroArchiveByTypeAndDate($type, $date_limite) {        
        $rows = $this->client
            ->startkey(array($type, "1990-01-01"))
            ->endkey(array($type, $date_limite))
            ->limit(1)            
            ->getView($this->design, $this->view)->rows;

        foreach($rows as $row) {

            return $row->key[self::KEYS_NUMERO_ARCHIVE];
        }

        return 0;
    }
    
}  