<?php
class Etablissement extends BaseEtablissement {

    protected $_interpro = null;

    const STATUT_ACTIF = "ACTIF";
    const STATUT_ARCHIVER = "ARCHIVER";
    const STATUT_DELIER = "DELIER";
    const STATUT_CSV = "CSV";
    
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

    private function cleanPhone($phone, $idcompte) {
        $phone = preg_replace('/[^0-9\+]+/', '', $phone);
        $phone = preg_replace('/^00/', '+', $phone);
        $phone = preg_replace('/^0/', '+33', $phone);

        if (strlen($phone) == 9 && preg_match('/^[64]/', $phone) )
        	$phone = '+33'.$phone;

        if (!preg_match('/^\+/', $phone) || (strlen($phone) != 12 && preg_match('/^\+33/', $phone)))
        	echo("$phone n'est pas un téléphone correct pour ".$this->_id."\n");
        
        return $phone;
    }

    public function setFax($fax) {
        if ($fax)
            $this->_set('fax', $this->cleanPhone($fax));
    }
    public function setTelephone($phone, $idcompte = null) {
        if ($phone)
            $this->_set('telephone', $this->cleanPhone($phone));
    }
    
    public function getDenomination() {

    	return ($this->nom) ? $this->nom : $this->raison_sociale;
    }
        
    public function getFamilleType() 
    {
        $familleType = array(EtablissementClient::FAMILLE_PRODUCTEUR => 'acheteur',
                             EtablissementClient::FAMILLE_NEGOCIANT => 'vendeur',
                             EtablissementClient::FAMILLE_COURTIER => 'mandataire');
        return $familleType[$this->famille];
    }

	public function getDepartement()
	{
		if ($this->siege->code_postal) {
			return substr($this->siege->code_postal, 0, 2);
		}
		return null;
	}

    public function getDroits() {
        /*$droits = array();

        if (in_array($this->famille, array(EtablissementClient::FAMILLE_PRODUCTEUR))) {
            $droits[] = TiersSecurityUser::CREDENTIAL_DROIT_DRM;
        }
        
        $droits[] = TiersSecurityUser::CREDENTIAL_DROIT_VRAC;

        return $droits;*/
    	return EtablissementFamilles::getDroitsByFamilleAndSousFamille($this->famille, $this->sous_famille);
    }   
    
}
