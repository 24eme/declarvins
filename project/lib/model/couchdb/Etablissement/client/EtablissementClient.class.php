<?php

class EtablissementClient extends acCouchdbClient {
   
    /**
     *
     * @return EtablissementClient
     */
    public static function getInstance() {
        return acCouchdbManager::getClient("Etablissement");
    }
    
    /**
     *
     * @param string $login
     * @param integer $hydrate
     * @return Etablissement 
     */
    public function retrieveById($id, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return parent::retrieveDocumentById('ETABLISSEMENT-'.$id, $hydrate);
    }

    public function retrieveOrCreateById($id, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        $etab =  parent::retrieveDocumentById('ETABLISSEMENT-'.$id, $hydrate);
	if (!$etab) {
	  $etab = new Etablissement();
	  $etab->_id = 'ETABLISSEMENT-'.$id;
	}
	return $etab;
    }

  	public function findByInterpro($interpro) {
    	return $this->startkey(array($interpro))
              ->endkey(array($interpro, array()))->getView('etablissement', 'all');
  	}
    
    public function makeLibelle($datas) {
        $etablissementLibelle = '';
        if ($nom = $datas[2]) {
            $etablissementLibelle .= $nom;
        }
        if ($rs = $datas[4]) {
            if ($etablissementLibelle) {
                $etablissementLibelle .= ' / ';
            }
            $etablissementLibelle .= $rs;
        }
        $etablissementLibelle .= ' ('.$datas[3];
        if ($siret = $datas[5]) {
            $etablissementLibelle .= ' / '.$siret;
        }
        if ($cvi = $datas[6]) {
            $etablissementLibelle .= ' / '.$cvi;
        }
        $etablissementLibelle .= ') '.$datas[7].' '.$datas[8];
        return trim($etablissementLibelle);
    }

}
