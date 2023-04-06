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

    public function getRegion() {
    	return ($this->exist('region'))? $this->_get('region') : EtablissementClient::REGION_CVO;
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
        else
            $this->_set('fax', null);
    }
    public function setTelephone($phone, $idcompte = null) {
        if ($phone)
            $this->_set('telephone', $this->cleanPhone($phone, $idcompte));
        else
            $this->_set('fax', null);
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

        $societe = $this->getGenerateSociete();
        $societe->save();

    	parent::save();
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
        return $this->hasZone(ConfigurationZoneClient::ZONE_LANGUEDOC);
    }

    public function hasZone($zone)
    {
        return $this->zones->exist($zone);
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

    public function getMasterCompte() {
      return $this->getCompteObject();
    }

    public function getGenerateSociete()
    {
      $societe = $this->getSociete();
      if (!$societe) {
        $societe = new Societe();
        $societe->addEtablissement($this);
        $societe->add("date_creation", date('Y-m-d'));
        $societe->constructId();
        $societe->commentaire = "Généré automatiquement a partir de l'établissement $this->_id";
        $this->societe = $societe->_id;
      }
      $societe->raison_sociale = $this->raison_sociale;
      $societe->type_societe = SocieteClient::TYPE_OPERATEUR;
      $societe->identifiant = $this->identifiant;
      $societe->siret = $this->siret;
      $societe->statut = $this->statut;
      $societe->cooperative = ($this->sous_famille == EtablissementFamilles::SOUS_FAMILLE_CAVE_COOPERATIVE)? true : false;
      $societe->email = $this->email;
      $societe->telephone = $this->telephone;
      $societe->fax = $this->fax;
      $societe->no_tva_intracommunautaire = $this->no_tva_intracommunautaire;
      $societe->siege->adresse = $this->siege->adresse;
      $societe->siege->code_postal = $this->siege->code_postal;
      $societe->siege->commune = $this->siege->commune;
      $societe->siege->pays = $this->siege->pays;
      $societe->compte_societe = $this->compte;
      $societe->remove('contacts');
      $societe->add('contacts');
      $societe->contacts->getOrAdd($this->compte);
      return $societe;
    }

    public function isActif() {
        return $this->statut && ($this->statut == self::STATUT_ACTIF);
    }

    public function getSociete() {
        $identifiant = ($this->_get('societe'))? $this->_get('societe') : $this->identifiant;
        return SocieteClient::getInstance()->find($identifiant);
    }

    public function hasSociete() {
        return $this->_get('societe');
    }

    public function getCodeInsee() {
        if (!$this->siege->exist('code_insee') && $this->cvi) {
            $this->siege->add('code_insee',  substr($this->cvi, 0, 5));
        }
        if ($this->siege->exist('code_insee')) {
            return $this->siege->code_insee;
        }
        return null;
    }

    public function getEmailTeledeclaration() {
       return null;
    }
}
