<?php

/**
 * Model for DRM
 *
 */
class DRM extends BaseDRM implements InterfaceVersionDocument {

    const NOEUD_TEMPORAIRE = 'TMP';
    const DEFAULT_KEY = 'DEFAUT';

    protected $version_document = null;
    protected $suivante = null;

    public function  __construct() {
        parent::__construct();   
        $this->initDocuments();
        $config_certifications = ConfigurationClient::getCurrent()->declaration->certifications;
        foreach ($config_certifications as $key => $config_certification) {
        	$this->declaration->certifications->add($key);
        }
    }

    public function __clone() {
        parent::__clone();
        $this->initDocuments();
    }   

    protected function initDocuments() {
       $this->version_document = new VersionDocument($this); 
    }

    public function constructId() {

        $this->set('_id', DRMClient::getInstance()->buildId($this->identifiant, 
                                                            $this->periode, 
                                                            $this->version));
    }

    public function getPeriodeAndVersion() {

        return DRMClient::getInstance()->buildPeriodeAndVersion($this->periode, $this->version);
    }

    public function getMois() {
        
        return DRMClient::getInstance()->getMois($this->periode);
    }

    public function getAnnee() {
        
        return DRMClient::getInstance()->getAnnee($this->periode);
    }

    public function getDate() {
        
        return DRMClient::getInstance()->buildDate($this->periode);
    }

    public function setPeriode($periode) {
        $this->campagne = DRMClient::getInstance()->buildCampagne($periode);

        return $this->_set('periode', $periode);
    }

    public function getProduit($hash, $labels = array()) {
        if (!$this->exist($hash)) {

            return false;
        }

        return $this->get($hash)->details->getProduit($labels);
    }

    public function addProduit($hash, $labels = array()) {
      if ($p = $this->getProduit($hash, $labels)) {
        return $p;
      }
      $detail = $this->getOrAdd($hash)->details->addProduit($labels);
      $detail->updateVolumeBloque();
      return $detail;
    }

    public function getDepartement() {
        if($this->declarant->siege->code_postal) {
          return substr($this->declarant->siege->code_postal, 0, 2);
        }

        return null;
    }

    public function getModeDeSaisieLibelle()
    {
        
        return DRMClient::getInstance()->getModeDeSaisieLibelle($this->mode_de_saisie);
    }

    public function getDetails() {
        
        return $this->declaration->getProduits();
    }

    public function getDetailsAvecVrac() {
        $details = array();
        foreach ($this->getDetails() as $d) {
	        if ($d->sorties->vrac && $d->canHaveVrac()) {
	           $details[] = $d;
            }
        }
        
        return $details;
    }

    public function generateSuivante() 
    {

        return $this->generateSuivanteByPeriode(DRMClient::getInstance()->getPeriodeSuivante($this->periode));
    }

    public function generateSuivanteByPeriode($periode) 
    {
        $is_just_the_next_periode = (DRMClient::getInstance()->getPeriodeSuivante($this->periode) == $periode);
        $keepStock = ($periode > $this->periode);

        $drm_suivante = clone $this;
    	$drm_suivante->init(array('keepStock' => $keepStock, 'next_campagne' => DRMClient::getInstance()->buildCampagne($periode)));
        $drm_suivante->update();
        $drm_suivante->periode = $periode;

        if ($is_just_the_next_periode) {
            $drm_suivante->precedente = $this->_id;
        }
        return $drm_suivante;
    }
    
    public function init($params = array()) {
      	parent::init($params);
      	$keepStock = isset($params['keepStock']) ? $params['keepStock'] : true;
      	$nextCampagne = isset($params['next_campagne']) ? $params['next_campagne'] : $this->campagne;
        $this->remove('douane');
        $this->add('douane');
        $this->remove('declarant');
        $this->add('declarant');
        $this->remove('editeurs'); 
        $this->add('editeurs'); 

        $this->version = null;
        $this->raison_rectificative = null;
        $this->etape = null;
        $this->precedente = null;
        $this->identifiant_drm_historique = null;
        $this->identifiant_ivse = null;
        
        if (!$keepStock || ($nextCampagne != $this->campagne)) {
        	$this->declaratif->adhesion_emcs_gamma = null;
        	$this->declaratif->paiement->douane->frequence = null;
        	$this->declaratif->paiement->douane->moyen = null;
        	$this->declaratif->paiement->cvo->frequence = null;
        	$this->declaratif->paiement->cvo->moyen = null;
        	$this->declaratif->caution->dispense = null;
        	$this->declaratif->caution->organisme = null;
        }
        $this->declaratif->defaut_apurement = null;
        $this->declaratif->daa->debut = null;
        $this->declaratif->daa->fin = null;
        $this->declaratif->dsa->debut = null;
        $this->declaratif->dsa->fin = null;
                
        $this->devalide();
    }

    public function setDroits() 
    {
        $this->remove('droits');
        $this->add('droits');
    	$mergeSorties = array();
    	$mergeEntrees = array();
    	if ($this->getInterpro()->getKey() == Interpro::INTERPRO_KEY.Interpro::INTER_RHONE_ID) {
    		$mergeSorties = DRMDroits::getDroitSortiesInterRhone();
    		$mergeEntrees = DRMDroits::getDroitEntreesInterRhone();
    	}
        foreach ($this->getDetails() as $detail) {
        	$droitCvo = $detail->getDroit(DRMDroits::DROIT_CVO);
        	$droitDouane = $detail->getDroit(DRMDroits::DROIT_DOUANE);
        	if ($droitCvo) {
        		$this->droits->getOrAdd(DRMDroits::DROIT_CVO)->getOrAdd($droitCvo->code)->integreVolume($detail->sommeLignes(DRMDroits::getDroitSorties($mergeSorties)), $detail->sommeLignes(DRMDroits::getDroitEntrees($mergeEntrees)), $droitCvo->taux, 0, $droitCvo->libelle);
        	}
        	if ($droitDouane) {
        		$this->droits->getOrAdd(DRMDroits::DROIT_DOUANE)->getOrAdd($droitDouane->code)->integreVolume($detail->sommeLignes(DRMDroits::getDouaneDroitSorties()), $detail->sommeLignes(DRMDroits::getDroitEntrees($mergeEntrees)), $droitDouane->taux, $this->getReportByDroit(DRMDroits::DROIT_DOUANE, $droitDouane), $droitDouane->libelle);
        	}
        }
    }
    
    public function getReportByDroit($type, $droit) 
    {
    	$drmPrecedente = $this->getPrecedente();
    	if ($drmPrecedente && !$drmPrecedente->isNew()) {
    		if ($drmPrecedente->droits->get($type)->exist($droit->code)) {
    			return $drmPrecedente->droits->get($type)->get($droit->code)->cumul;
    		}
    	}
    	return 0;
    }
    
	public function detailHasMouvementCheck() {
        foreach($this->getDetails() as $d) {
            if($d->hasMouvementCheck()) {
                return true;
            }
        }

        return false;
    }

    public function getEtablissement() {
    	if (!$this->identifiant) {
		    throw new Exception('pas d\'établissement saisi pour '.$this->_id);
        }
        
        $e = EtablissementClient::getInstance()->retrieveById($this->identifiant);
	    
        if (!$e) {
	       throw new Exception('pas d\'établissement correspondant à '.$this->identifiant);
	    }
	   
        return $e;
    }
    
    public function setEtablissementInformations($etablissement = null)
    {
    	if (!$etablissement) {
    		$etablissement = $this->getEtablissement();
    	}
    	$this->declarant->nom = $etablissement->nom;
  		$this->declarant->raison_sociale = $etablissement->raison_sociale;
  		$this->declarant->siret = $etablissement->siret;
  		$this->declarant->cni = $etablissement->cni;
  		$this->declarant->cvi = $etablissement->cvi;
  		$this->declarant->siege->adresse = $etablissement->siege->adresse;
  		$this->declarant->siege->code_postal = $etablissement->siege->code_postal;
  		$this->declarant->siege->commune = $etablissement->siege->commune;
  		$this->declarant->siege->pays = $etablissement->siege->pays;
  		$this->declarant->comptabilite->adresse = $etablissement->comptabilite->adresse;
  		$this->declarant->comptabilite->code_postal = $etablissement->comptabilite->code_postal;
  		$this->declarant->comptabilite->commune = $etablissement->comptabilite->commune;
  		$this->declarant->comptabilite->pays = $etablissement->comptabilite->pays;
  		$this->declarant->no_accises = $etablissement->no_accises;
  		$this->declarant->no_tva_intracommunautaire = $etablissement->no_tva_intracommunautaire;
  		$this->declarant->email = $etablissement->email;
  		$this->declarant->telephone = $etablissement->telephone;
  		$this->declarant->fax = $etablissement->fax;
  		$this->declarant->famille = $etablissement->famille;
  		$this->declarant->sous_famille = $etablissement->sous_famille;
  		$this->declarant->service_douane = $etablissement->service_douane;
    }
    
    public function getInterpro() {
    	
        if ($this->getEtablissement())
            return $this->getEtablissement()->getInterproObject();
    }
    
    public function getHistorique() {

        return $this->store('historique', array($this, 'getHistoriqueAbstract'));
    }

    public function getPrecedente() {
        if ($this->exist('precedente') && $this->_get('precedente')) {
            
            return DRMClient::getInstance()->find($this->_get('precedente'));
        } else {
            
            return new DRM();
        }
    }

    public function getSuivante() {
        if(is_null($this->suivante)) {
            $periode = DRMClient::getInstance()->getPeriodeSuivante($this->periode);
            $campagne = DRMClient::getInstance()->buildCampagne($periode);
            if ($campagne != $this->campagne) {
                return null;
            } 
            $this->suivante = DRMClient::getInstance()->findMasterByIdentifiantAndPeriode($this->identifiant, $periode);
        }
      
       return $this->suivante;
    }

    public function isSuivanteCoherente() {
        $drm_suivante = $this->getSuivante();

        if(!$drm_suivante) {

            return true;
        }

        if ($this->validation()->hasError('stock')) {

           return false;
        }
        
        if ($this->droits->douane->getCumul() != $drm_suivante->droits->douane->getCumul()) {

           return false;
        }

        return false;        
    }

    public function devalide() {
        $this->etape = null;
        $this->valide->identifiant = null;
        $this->valide->date_saisie = null;
        $this->valide->date_signee = null;
    }

    public function isValidee() {

        return ($this->valide->date_saisie);
    }

    public function validate($options = null) {
        $this->update();
                
        if ($this->hasApurementPossible()) {
            $this->apurement_possible = 1;
        }

        if ($next_drm = $this->getSuivante()) {
            $next_drm->precedente = $this->_id;
            $next_drm->save();
        }
    
        $this->storeIdentifiant($options);
        $this->storeDates();
        $this->storeDroits($options);
        $this->setInterpros();
        $this->updateVrac();

        if($this->getSuivante() && $this->isSuivanteCoherente()) {
            $this->getSuivante()->precedente = $this->get('_id');
            $this->getSuivante()->save();
        }
    }

    public function storeDroits($options) {
        if (!isset($options['no_droits']) || !$options['no_droits']) {
           $this->setDroits();
        }
    }

    public function storeIdentifiant($options) {
        $identifiant = $this->identifiant;

        if ($options && is_array($options)) {
            if (isset($options['identifiant']))
                $identifiant = $options['identifiant'];
        }

        $this->valide->identifiant = $identifiant;
    }

    public function storeDates() {
        if (!$this->valide->date_saisie) {
           $this->valide->add('date_saisie', date('c'));
        }

        if (!$this->valide->date_signee) {
           $this->valide->add('date_signee', date('c'));
        }

    }

    public function updateVrac() {
    	foreach ($this->getDetails() as $detail) {
			foreach ($detail->vrac as $numero => $vrac) {
				$volume = $vrac->volume;
				if ($this->hasVersion() && !$this->isModifiedMother($vrac, 'volume')) {
					continue;
					
				}
				
				if ($this->hasVersion() && $this->getMother()->exist($vrac->getHash())) {
					$volume = $volume - $this->getMother()->get($vrac->getHash())->volume;
				}
				
				if ($volume == 0) {
					continue;
				}
				$contrat = VracClient::getInstance()->findByNumContrat($numero);
				$contrat->integreVolumeEnleve($volume);
				$contrat->save();
			}
      	}
    }

    public function setInterpros() {
        $i = $this->getInterpro();
        if ($i) {
	        $this->interpros->add(0,$i->getKey());
        }
    }

    public function save() {
        if (!preg_match('/^2\d{3}-[01][0-9]$/', $this->periode)) {
            throw new sfException('Wrong format for periode ('.$this->periode.')');
        }
        if ($user = $this->getUser()) {
        	if ($user->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
        		$compte = $user->getCompte();
        		$canInsertEditeur = true;
        		if ($lastEditeur = $this->getLastEditeur()) {
        			$diff = Date::diff($lastEditeur->date_modification, date('c'), 'i');
        			if ($diff < 25) {
        				$canInsertEditeur = false;
        			}
        		}
        		if ($canInsertEditeur) {
        			$this->addEditeur($compte);
        		}
        	}
        }
        if ($this->isNew()) {
        	$etablissement = $this->getEtablissement();
        	$this->etablissement_num_interne = $etablissement->num_interne;
        }

        return parent::save();
    }

    protected function getHistoriqueAbstract() {
        
        return DRMClient::getInstance()->getDRMHistorique($this->identifiant);
    }

    private function getTotalDroit($type) {
        $total = 0;
        foreach ($this->declaration->certifications as $certification) {
            foreach ($certification->appellations as $appellation) {
                $total += $appellation->get('total_'.$type);
            }
        }
        
        return $total;  
    }

    private function interpretHash($hash) {
      if (!preg_match('|declaration/certifications/([^/]*)/appellations/([^/]*)/|', $hash, $match)) {
        
        throw new sfException($hash." invalid");
      }
      
      return array('certification' => $match[1], 'appellation' => $match[2]);
    }

    private function setDroit($type, $appellation) {
        $configurationDroits = $appellation->getConfig()->interpro->get($this->getInterpro()->get('_id'))->droits->get($type)->getCurrentDroit($this->periode);
        $droit = $appellation->droits->get($type);
        $droit->ratio = $configurationDroits->ratio;
        $droit->code = $configurationDroits->code;
        $droit->libelle = $configurationDroits->libelle;
    }
    
    public function isPaiementAnnualise() {
    	
        return $this->declaratif->paiement->douane->isAnnuelle();
    }

    public function getHumanDate() {
	   setlocale (LC_TIME, 'fr_FR.utf8','fra'); 
	   
       return strftime('%B %Y', strtotime($this->periode.'-01'));
    }
    public function getEuValideDate() {
	   
       return strftime('%d/%m/%Y', strtotime($this->valide->date_signee));
    }
    
    public function isDebutCampagne() {
        return DRMPaiement::isDebutCampagne(DRMClient::getInstance()->getMois($this->periode));
    }
    public function getCampagnePrecedente() {
    	$annee = preg_replace('/([0-9]{4})-([0-9]{4})/', '$1', $this->campagne);
    	return ($annee-1).'-'.$annee;
    }
    public function hasDaidsCampagnePrecedente() {
    	$campagne = $this->getCampagnePrecedente();
    	return (DAIDSClient::getInstance()->findMasterByIdentifiantAndPeriode($this->identifiant, $campagne))? true : false;
    }
    
    public function hasDaids() {
    	return (DAIDSClient::getInstance()->findMasterByIdentifiantAndPeriode($this->identifiant, $this->campagne))? true : false;
    }

    public function getCurrentEtapeRouting() {
    	$etape = sfConfig::get('app_drm_etapes_'.$this->etape);
    	
        return $etape['url'];
    }

    public function setCurrentEtapeRouting($etape) {
    	if (!$this->isValidee()) {
    		$this->etape = $etape;
    		$this->getDocument()->save();
    	}
    }

    public function hasApurementPossible() {
    	if (
    		$this->declaratif->daa->debut ||
    		$this->declaratif->daa->fin ||
    		$this->declaratif->dsa->debut ||
    		$this->declaratif->dsa->debut ||
    		$this->declaratif->adhesion_emcs_gamma
    	) {
    		
            return true;
    	} else {
    		
            return false;
    	}
    }
    public function hasVrac() {
    	$detailsVrac = $this->getDetailsAvecVrac();
    	
        return (count($detailsVrac) > 0) ;
    }
    
    public function hasConditionneExport() {
      
      return ($this->declaration->getTotalByKey('sorties/export') > 0);
    }

    public function hasMouvementAuCoursDuMois() {
      
      return $this->hasVrac() || $this->hasConditionneExport();
    }

    public function isEnvoyee() {
    	if (!$this->exist('valide'))
    		return false;
    	if (!$this->valide->exist('status'))
    		return false;
    	if ($this->valide->status != DRMClient::VALIDE_STATUS_VALIDEE_ENVOYEE && $this->valide->status != DRMClient::VALIDE_STATUS_VALIDEE_RECUE) {
    		return false;
    	} else {
    		return true;
    	}
    }
    /*
     * Pour les users administrateur
     */
    public function canSetStockDebutMois() {
    	if (!$this->getPrecedente()) {
    		return true;
    	} elseif ($this->getPrecedente() && $this->getPrecedente()->isNew()) {
    		return true;
    	} elseif ($this->isDebutCampagne()) {
    		return true;
    	} else {
    		return false;
    	}
    }
    
    public function hasProduits() {
    	return (count($this->declaration->getProduits()) > 0)? true : false;
    }
    
    public function hasEditeurs() {
    	return (count($this->editeurs) > 0);
    }
    
    public function getLastEditeur() {
    	if ($this->hasEditeurs()) {
    		$editeurs = $this->editeurs->toArray();
    		return array_pop($editeurs);
    	} else {
    		return null;
    	}
    }
    
    public function getUser() {
    	try {
    		return sfContext::getInstance()->getUser();
    	} catch (sfException $e) {
    		return null;
    	}
    	return null;
    }
    
    public function addEditeur($compte) {
    	$editeur = $this->editeurs->add();
    	$editeur->compte = $compte->_id;
    	$editeur->nom = $compte->nom;
    	$editeur->prenom = $compte->prenom;
    	$editeur->date_modification = date('c');
    }
    
    public function isRectificativeEnCascade() {
    	if (!$this->isRectificative()) {
    		return false;
    	}
    	$mother = $this->getMother();
    	if ($mother && $mother->getPrecedente() && $this->getPrecedente()) {
    		return ($mother->getPrecedente()->_id != $this->getPrecedente()->_id)? true : false;
    	} 
    	return false;
    }

    public function isSupprimable() {

        return !$this->isValidee() && !$this->isRectificativeEnCascade();
    }

    public function isSupprimableOperateur() {

        return !$this->isEnvoyee() && !$this->isRectificativeEnCascade();
    }

    public function validation($options = null) { 

        return new DRMValidation($this, $options);
    }

    /**** VERSION ****/

    public static function buildVersion($rectificative, $modificative) {

        return VersionDocument::buildVersion($rectificative, $modificative);
    }

    public static function buildRectificative($version) {

        return VersionDocument::buildRectificative($version);
    }

    public static function buildModificative($version) {

        return VersionDocument::buildModificative($version);
    }

    public function getVersion() {

        return $this->_get('version');
    }

    public function hasVersion() {

        return $this->version_document->hasVersion();
    }

    public function isVersionnable() {
        if (!$this->isValidee()) {
           
           return false;
        }

        return $this->version_document->isVersionnable();
    }

    public function getRectificative() {

        return $this->version_document->getRectificative();
    }

    public function isRectificative() {

        return $this->version_document->isRectificative();
    }

    public function isRectifiable() {
        
        return $this->version_document->isRectifiable();
    }

    public function getModificative() {

        return $this->version_document->getModificative();
    }

    public function isModificative() {

        return $this->version_document->isModificative();
    }

    public function isModifiable() {

        return $this->version_document->isModifiable();
    }

    public function getPreviousVersion() {

       return $this->version_document->getPreviousVersion();
    }

    public function getMasterVersionOfRectificative() {
        return DRMClient::getInstance()->getMasterVersionOfRectificative($this->identifiant, 
                                                                 $this->periode, 
                                                                 $this->getRectificative() - 1);
    }

    public function needNextVersion() {

       return $this->version_document->needNextVersion() || !$this->isSuivanteCoherente();      
    }

    public function getMaster() {

        return $this->version_document->getMaster();
    }

    public function isMaster() {

        return $this->version_document->isMaster();
    }

    public function findMaster() {

        return DRMClient::getInstance()->findMasterByIdentifiantAndPeriode($this->identifiant, $this->periode);
    }

    public function findDocumentByVersion($version) {

        return DRMClient::getInstance()->find(DRMClient::getInstance()->buildId($this->identifiant, $this->periode, $version));
    }

    public function getMother() {

        return $this->version_document->getMother();   
    }

    public function motherGet($hash) {

        return $this->version_document->motherGet($hash);
    }

    public function motherExist($hash) {

        return $this->version_document->motherExist($hash);
    }

    public function motherHasChanged() {
        if ($this->declaration->total != $this->getMother()->declaration->total) {
           
           return true;
        }

        if (count($this->getDetails()) != count($this->getMother()->getDetails())) {
           
           return true;
        }

        if ($this->droits->douane->getCumul() != $this->getMother()->droits->douane->getCumul()) {
           
           return true;
        }

        return false;
    }

    public function getDiffWithMother() {

        return $this->version_document->getDiffWithMother();
    }

    public function isModifiedMother($hash_or_object, $key = null) {
        return $this->version_document->isModifiedMother($hash_or_object, $key);
    }

    public function generateRectificative() {

        return $this->version_document->generateRectificative();
    }

    public function generateModificative() {

        return $this->version_document->generateModificative();
    }

    public function generateNextVersion() {
        if (!$this->hasVersion()) {

            return $this->version_document->generateRectificativeSuivante();
        }

        return $this->version_document->generateNextVersion();
    }

    public function listenerGenerateVersion($document) {
        $document->devalide();
    }

    public function listenerGenerateNextVersion($document) {
        $this->replicate($document);
        $document->update();
    }

    protected function replicate($drm) {
        foreach($this->getDiffWithMother() as $key => $value) {
            $this->replicateDetail($drm, $key, $value, 'total', 'total_debut_mois');
            $this->replicateDetail($drm, $key, $value, 'total_interpro', 'total_debut_mois_interpro');
            $this->replicateDetail($drm, $key, $value, 'stocks_fin/bloque', 'stocks_debut/bloque');
            $this->replicateDetail($drm, $key, $value, 'stocks_fin/warrante', 'stocks_debut/warrante');
            $this->replicateDetail($drm, $key, $value, 'stocks_fin/instance', 'stocks_debut/instance');
            $this->replicateDetail($drm, $key, $value, 'stocks_fin/commercialisable', 'stocks_debut/commercialisable');
        }
    }

    protected function replicateDetail(&$drm, $key, $value, $hash_match, $hash_replication) {
        if (preg_match('|^(/declaration/certifications/.+/appellations/.+/mentions/.+/lieux/.+/couleurs/.+/cepages/.+/details/.+)/'.$hash_match.'$|', $key, $match)) {
            $detail = $this->get($match[1]);
            if (!$drm->exist($detail->getHash())) {
                $drm->addProduit($detail->getCepage()->getHash(), $detail->labels->toArray());
            }
            $drm->get($detail->getHash())->set($hash_replication, $value);
        }
    }
    /**** FIN DE VERSION ****/
}
