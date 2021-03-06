<?php
class Etablissement extends BaseEtablissement {

    protected $_interpro = null;
    protected $_compte = null;
    protected $droit = null;

    const STATUT_ACTIF = "ACTIF";
    const STATUT_ARCHIVE = "ARCHIVE";
    const STATUT_DELIE = "DELIE";
    const STATUT_CSV = "CSV";

    public function isTransmissionCiel() {
        if (!$this->canAdhesionCiel()) {
            return false;
        }
    	return ($this->transmission_ciel)? true : false;
    }

    public function canAdhesionCiel() {
        if ($this->famille == EtablissementFamilles::FAMILLE_PRODUCTEUR || $this->sous_famille == EtablissementFamilles::SOUS_FAMILLE_VINIFICATEUR) {
            return true;
        }
        return false;
    }


    public function getCompteObject() {
        if (is_null($this->_compte) && $this->compte) {
            $this->_compte = _CompteClient::getInstance()->find($this->compte);
        }

        return $this->_compte;
    }

    public function getRegion()
    {
    	return null;
    }

    public function getInterproObject() {
        if (is_null($this->_interpro) && $this->interpro) {
            $this->_interpro = InterproClient::getInstance()->find($this->interpro);
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
        	$phone = '+33 (0)'.$phone;

        /*if (!preg_match('/^\+/', $phone) || (strlen($phone) != 12 && preg_match('/^\+33/', $phone)))
        	echo("$phone n'est pas un téléphone correct pour ".$idcompte."\n");*/

        return $phone;
    }

    public function setFax($fax, $idcompte = null) {
        if ($fax)
            $this->_set('fax', $this->cleanPhone($fax, $idcompte));
    }
    public function setTelephone($phone, $idcompte = null) {
        if ($phone)
            $this->_set('telephone', $this->cleanPhone($phone, $idcompte));
    }

    public function getDenomination() {

    	return ($this->nom) ? $this->nom : $this->raison_sociale;
    }

    public function getFamilleType()
    {
        $familleType = array(EtablissementFamilles::FAMILLE_PRODUCTEUR => 'vendeur',
                             EtablissementFamilles::FAMILLE_NEGOCIANT => 'acheteur',
                             EtablissementFamilles::FAMILLE_COURTIER => 'mandataire');
        return $familleType[$this->famille];
    }

    public function isViticulteur() {
        return ($this->famille == EtablissementFamilles::FAMILLE_PRODUCTEUR && $this->sous_famille != EtablissementFamilles::SOUS_FAMILLE_CAVE_COOPERATIVE);
    }

    public function isNegociant() {
        return ($this->famille == EtablissementFamilles::FAMILLE_NEGOCIANT);
    }

    public function isCaveCooperative() {
        return ($this->sous_famille == EtablissementFamilles::SOUS_FAMILLE_CAVE_COOPERATIVE);
    }

	public function getDepartement()
	{
		if ($this->siege->code_postal) {
			return substr($this->siege->code_postal, 0, 2);
		}
		return null;
	}

    public function getDroit() {
        if (is_null($this->droit)) {

            $this->droit = new EtablissementDroit($this);
        }

        return $this->droit;
    }

    public function hasDroit($droit)
    {

        return $this->getDroit()->has($droit);
    }

    public function getDroits() {
    	return ($this->exist('droits'))? array_merge_recursive($this->_get('droits')->toArray(), EtablissementFamilles::getDroitsByFamilleAndSousFamille($this->famille, $this->sous_famille)) : EtablissementFamilles::getDroitsByFamilleAndSousFamille($this->famille, $this->sous_famille);
    }

    public function save() {
    	if (!$this->famille && !$this->sous_famille) {
    		$this->famille = EtablissementFamilles::FAMILLE_PRODUCTEUR;
    		$this->sous_famille = EtablissementFamilles::SOUS_FAMILLE_CAVE_PARTICULIERE;
    	}
    	if ($bilan = $this->getBilan()) {
    		$bilan->updateEtablissement($this);
    		$bilan->save();
    	}
    	parent::save();
    }

    public function getBilan()
    {
    	return BilanClient::getInstance()->findByIdentifiantAndType($this->identifiant, 'DRM');
    }


    public function __toString() {

        return sprintf('%s (%s)', $this->nom, $this->identifiant);
    }

    public function getVolumeBloque($produit, $atDate = null)
    {
    	$atDate = ($atDate)? $atDate : date('Y-m-d');
    	if ($this->produits->exist($produit)) {
    		$volumes = $this->produits->get($produit)->volume_bloque->toArray();
    		if (count($volumes) > 0) {
    			ksort($volumes);
    			$v = null;
    			foreach ($volumes as $date => $volume) {
    				if ($date <= $atDate) {
    					$v = $volume->volume;
    				}
    			}
    			return $v;
    		}
    	}
    	return null;
    }

    public function getConfigurationZones($onlyBase = false)
    {
    	$zones = array();
    	$interpros = InterproClient::getInstance()->getInterprosObject();
    	$baseZones = array();
    	foreach ($interpros as $i) {
    		$baseZones[] = $i->zone;
    	}
    	foreach ($this->zones as $configurationZoneId => $zoneInfos) {
    		if ($onlyBase && !in_array($configurationZoneId, $baseZones)) {
    			continue;
    		}
    		$zones[$configurationZoneId] = ConfigurationZoneClient::getInstance()->find($configurationZoneId);
    	}
    	return $zones;
    }

    public function hasZoneIS()
    {
        return $this->zones->exist(ConfigurationZoneClient::ZONE_LANGUEDOC);
    }

    public function hasDocuments()
    {
        return (count(PieceAllView::getInstance()->getPiecesByEtablissement($this->identifiant)) > 0);
    }

    public function makeLibelle()
    {
    	$datas = array();
    	$datas[EtablissementAllView::KEY_NOM] = $this->nom;
    	$datas[EtablissementAllView::KEY_RAISON_SOCIALE] = $this->raison_sociale;
    	$datas[EtablissementAllView::KEY_IDENTIFIANT] = $this->identifiant;
    	$datas[EtablissementAllView::KEY_FAMILLE] = $this->famille;
    	$datas[EtablissementAllView::KEY_COMMUNE] = $this->siege->commune;
    	$datas[EtablissementAllView::KEY_CODE_POSTAL] = $this->siege->code_postal;
    	$datas[EtablissementAllView::KEY_PAYS] = $this->siege->pays;
    	$datas[EtablissementAllView::KEY_STATUT] = $this->statut;
    	$datas[EtablissementAllView::KEY_CORRESPONDANCE] = $this->correspondances;
    	return EtablissementAllView::makeLibelle($datas);
    }

    public function getMoisToSetStock()
    {
        if ($this->exist('mois_stock_debut') && $this->mois_stock_debut) {
            return $this->mois_stock_debut;
        }
        return DRMPaiement::NUM_MOIS_DEBUT_CAMPAGNE;
    }

    public function getAdresse()
    {
        return $this->siege->adresse;
    }

    public function getCodePostal()
    {
        return $this->siege->code_postal;
    }

    public function getCommune()
    {
        return $this->siege->commune;
    }
}
