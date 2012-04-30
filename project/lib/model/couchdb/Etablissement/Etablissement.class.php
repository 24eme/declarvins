<?php
class Etablissement extends BaseEtablissement {
    
    protected $_interpro = null;
    
    /**
     * @return _Compte
     */
    public function getInterproObject() {
        if (is_null($this->_interpro)) {
            $this->_interpro = InterproClient::getInstance()->retrieveDocumentById($this->interpro);
        }
        
        return $this->_interpro;
    }
    
    public function constructId() {
        $this->set('_id', 'ETABLISSEMENT-' . $this->identifiant);
    }
    
    public function getAllDRM() {
        return acCouchdbManager::getClient()->startkey(array($this->identifiant, null))
                                     ->endkey(array($this->identifiant, null))
                                     ->getView("drm", "all");
    }

    private function cleanPhone($phone) {
      $phone = preg_replace('/[^0-9\+]+/', '', $phone);
      $phone = preg_replace('/^0/', '+33', $phone);
      
      if (strlen($phone) == 9 && preg_match('/^[64]/', $phone) )
	$phone = '+33'.$phone;
      
      if (strlen($phone) != 12) 
	echo("$phone n'est pas un téléphone correct");
      return $phone;
      
    }

    public function setFax($fax) {
      if ($fax)
	$this->_set('fax', $this->cleanPhone($fax));
    }
    public function setTelephone($phone) {
      if ($phone)
	$this->_set('telephone', $this->cleanPhone($phone));
    }
    
}