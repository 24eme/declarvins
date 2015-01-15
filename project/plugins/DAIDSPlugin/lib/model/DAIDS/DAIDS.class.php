<?php
/**
 * Model for DAIDS
 *
 */

class DAIDS extends BaseDAIDS 
{

    protected $version_document = null;
   	protected static $entrepots = array(
   		'entrepot_a' => 'Entreprot A',
   		'entrepot_b' => 'Entreprot B',
   		'entrepot_c' => 'Entreprot C'
   	);

    public function  __construct() 
    {
        parent::__construct();   
        $this->initDocuments();
        $this->initEntrepots();
    }

    public function __clone() 
    {
        parent::__clone();
        $this->initDocuments();
    }   

    protected function initDocuments() 
    {
       $this->version_document = new VersionDocument($this); 
    }  

    protected function initEntrepots() 
    {
       foreach (self::$entrepots as $entrepot_id => $entrepot_libelle) {
       	$entrepot = $this->entrepots->add($entrepot_id);
       	$entrepot->libelle = $entrepot_libelle;
       } 
    }  

    public function initProduits() 
    {
       $drmsHistorique = new DRMHistorique($this->identifiant);
       if ($lastDrm = $drmsHistorique->getLastDRMByCampagne($this->periode)) {
       	foreach ($lastDrm->getDetails() as $detail) {
       		$d = $this->getDocument()->getOrAdd($detail->getHash());
       		$d->updateVolumeBloque();
       		$d->label_supplementaire = $detail->label_supplementaire;
       		$d->douane->taux = $detail->douane->taux;
       		$d->douane->code = $detail->douane->code;
       		$d->add('interpro', $detail->interpro);
       		if ($lastDrm->droits->exist(DAIDSDroits::DROIT_DOUANE) && $lastDrm->droits->get(DAIDSDroits::DROIT_DOUANE)->exist($detail->douane->code)) {
       			$d->douane->libelle = $lastDrm->droits->get(DAIDSDroits::DROIT_DOUANE)->get($detail->douane->code)->libelle;
       		} else {
       			$d->douane->libelle = $detail->douane->code;
       		}
       		$d->cvo->taux = $detail->cvo->taux;
       		$d->cvo->code = $detail->cvo->code;
       		if ($lastDrm->droits->exist(DAIDSDroits::DROIT_CVO) && $lastDrm->droits->get(DAIDSDroits::DROIT_CVO)->exist($detail->cvo->code)) {
       			$d->cvo->libelle = $lastDrm->droits->get(DAIDSDroits::DROIT_CVO)->get($detail->cvo->code)->libelle;
       		} else {
       			$d->cvo->libelle = $detail->cvo->code;
       		}
       		$d->stock_theorique = $detail->total;
       		$d->stock_mensuel_theorique = $detail->getStockTheoriqueMensuelByCampagne($this->periode);
       		foreach ($detail->labels as $label) {
       			$d->labels->add($label);
       		}
       		$d->millesime = $detail->millesime;
       		$labelLibelles = $detail->libelles_label->toArray();
        	foreach ($labelLibelles as $label => $libelle) {
        		$d->libelles_label->add($label, $libelle);
        	}
       	}
       	$this->getDocument()->update();
       }
    }
    
    public function updateStockTheorique()
    {
    	$drmsHistorique = new DRMHistorique($this->identifiant);
       	if ($lastDrm = $drmsHistorique->getLastDRMByCampagne($this->periode)) {
	    	foreach ($this->getDetails() as $detail) {
	    		$d = $lastDrm->getOrAdd($detail->getHash());
	    		$detail->stock_theorique = $d->total;
	    		$detail->stock_mensuel_theorique = $d->getStockTheoriqueMensuelByCampagne($this->periode);
	    	}
       		$this->getDocument()->update();
       	}
    }

    public function constructId() 
    {
        $this->set('_id', DAIDSClient::getInstance()->buildId($this->identifiant, 
                                                            $this->periode, 
                                                            $this->version));
    }
    
    public function getHistorique() 
    {
        return $this->store('historique', array($this, 'getHistoriqueAbstract'));
    }

    protected function getHistoriqueAbstract() 
    {
        return DAIDSClient::getInstance()->getDAIDSHistorique($this->identifiant);
    }

    public function getPeriodeAndVersion() 
    {
        return DAIDSClient::getInstance()->buildPeriodeAndVersion($this->periode, $this->version);
    }

    public function getEtablissement() 
    {
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

    public function generateSuivante($periode) 
    {
        $daids_suivante = clone $this;
    	$daids_suivante->init();
        $daids_suivante->update();
        $daids_suivante->periode = $periode;
        $daids_suivante->campagne = $periode;
        $daids_suivante->precedente = $this->_id;
        $daids_suivante->commentaires = null;
        $daids_suivante->devalide();
	    $daids_suivante->remove('editeurs'); 
	    $daids_suivante->add('editeurs'); 
	    $daids_suivante->remove('entrepots'); 
	    $daids_suivante->add('entrepots');
       
	    foreach ($daids_suivante->getDetails() as $detail) {
	    	$daids_suivante->remove($detail->getHash()); 
	    	//$daids_suivante->getOrAdd($detail->getHash());
	    }
	    $daids_suivante->initProduits();

        return $daids_suivante;
    }
    
    public function init($params = array()) 
    {
      	parent::init($params);
        $this->remove('douane');
        $this->add('douane');
        $this->remove('declarant');
        $this->add('declarant');
        $this->version = null;
        $this->raison_rectificative = null;
        $this->etape = null;
    }

    public function devalide() 
    {
        $this->etape = null;
        $this->valide->identifiant = null;
        $this->valide->date_saisie = null;
        $this->valide->date_signee = null;
    }

    public function getDetails() 
    {
        return $this->declaration->getProduits();
    }

    public function setDroits() 
    {
        $this->remove('droits');
        $this->add('droits');
        foreach ($this->getDetails() as $detail) {
        	$this->droits->getOrAdd(DAIDSDroits::DROIT_CVO)->getOrAdd($detail->get(DAIDSDroits::DROIT_CVO)->code)->integreVolume(($detail->total_manquants_taxables_cvo), $detail->get(DAIDSDroits::DROIT_CVO)->taux, $detail->get(DAIDSDroits::DROIT_CVO)->libelle);
        	$this->droits->getOrAdd(DAIDSDroits::DROIT_DOUANE)->getOrAdd($detail->get(DAIDSDroits::DROIT_DOUANE)->code)->integreVolume(($detail->total_manquants_taxables), $detail->get(DAIDSDroits::DROIT_DOUANE)->taux, $detail->get(DAIDSDroits::DROIT_DOUANE)->libelle);
        }
    }

    public function updateCvo() 
    {
        $this->remove('droits');
        $this->add('droits');
        foreach ($this->getDetails() as $detail) {
        	$this->droits->getOrAdd(DAIDSDroits::DROIT_CVO)->getOrAdd($detail->get(DAIDSDroits::DROIT_CVO)->code)->integreVolume(($detail->total_manquants_taxables_cvo), $detail->get(DAIDSDroits::DROIT_CVO)->taux, $detail->get(DAIDSDroits::DROIT_CVO)->libelle);
        	$this->droits->getOrAdd(DAIDSDroits::DROIT_DOUANE)->getOrAdd($detail->get(DAIDSDroits::DROIT_DOUANE)->code)->integreVolume(($detail->total_manquants_taxables), $detail->get(DAIDSDroits::DROIT_DOUANE)->taux, $detail->get(DAIDSDroits::DROIT_DOUANE)->libelle);
        }
    }

    public function setCurrentEtapeRouting($etape) 
    {
    	if (!$this->isValidee()) {
    		$this->etape = $etape;
    		$this->getDocument()->save();
    	}
    }

    public function isValidee() 
    {
        return ($this->valide->date_saisie);
    }

    public function isRectificative() 
    {
        return $this->version_document->isRectificative();
    }

    public function getMother() 
    {
        return $this->version_document->getMother();   
    }
    
    public function isRectificativeEnCascade() 
    {
    	if (!$this->isRectificative()) {
    		return false;
    	}
    	if ($mother = $this->getMother()) {
    		return ($mother->getPrecedente()->_id != $this->getPrecedente()->_id)? true : false;
    	} else {
    		return false;
    	}
    }

    public function getPrecedente() 
    {
        if ($this->exist('precedente') && $this->_get('precedente')) {            
            return DAIDSClient::getInstance()->find($this->_get('precedente'));
        } else {
            return new DAIDS();
        }
    }

    public function isSupprimable() 
    {
        return !$this->isValidee();
    }

    public function isSupprimableOperateur() 
    {
        return !$this->isEnvoyee() && !$this->isRectificativeEnCascade();
    }

    public function isEnvoyee() 
    {
    	if (!$this->exist('valide'))
    		return false;
    	if (!$this->valide->exist('status'))
    		return false;
    	if ($this->valide->status != DAIDSClient::VALIDE_STATUS_VALIDEE_ENVOYEE && $this->valide->status != DAIDSClient::VALIDE_STATUS_VALIDEE_RECUE) {
    		return false;
    	} else {
    		return true;
    	}
    }

    public function getRectificative()
    {
        return $this->version_document->getRectificative();
    }

    public static function buildVersion($rectificative, $modificative) 
    {
        return VersionDocument::buildVersion($rectificative, $modificative);
    }

    public function isMaster()
    {
        return $this->version_document->isMaster();
    }
    
    public function getMasterVersionOfRectificative() 
    {
        return DAIDSClient::getInstance()->getMasterVersionOfRectificative($this->identifiant, 
                                                                 $this->periode, 
                                                                 $this->getRectificative() - 1);
    }

    public function getMaster() 
    {
        return $this->version_document->getMaster();
    }

    public function findMaster() 
    {
        return DAIDSClient::getInstance()->findMasterByIdentifiantAndPeriode($this->identifiant, $this->periode);
    }

    public function getModificative() 
    {
        return $this->version_document->getModificative();
    }

    public function getModeDeSaisieLibelle()
    {
        return DAIDSClient::getInstance()->getModeDeSaisieLibelle($this->mode_de_saisie);
    }

    public static function buildRectificative($version) 
    {
        return VersionDocument::buildRectificative($version);
    }

    public static function buildModificative($version) 
    {
        return VersionDocument::buildModificative($version);
    }

    public function getCurrentEtapeRouting() 
    {
    	$etape = sfConfig::get('app_daids_etapes_'.$this->etape);
        return $etape['url'];
    }

    public function isModifiedMother($hash_or_object, $key = null) 
    {
        return $this->version_document->isModifiedMother($hash_or_object, $key);
    }

    public function hasVersion() 
    {
        return $this->version_document->hasVersion();
    }


    public function isModificative() 
    {
        return $this->version_document->isModificative();
    }

    public function validation($options = null) 
    { 
        return new DAIDSValidation($this, $options);
    }

    public function getSuivante() 
    {
       $periode = DAIDSClient::getInstance()->getPeriodeSuivante($this->periode);
       $next_daids = DAIDSClient::getInstance()->findMasterByIdentifiantAndPeriode($this->identifiant, $periode);
       if (!$next_daids) {
           return null;
       }
       
       return $next_daids;
    }

    public function storeIdentifiant($options) 
    {
        $identifiant = $this->identifiant;
        if ($options && is_array($options)) {
            if (isset($options['identifiant']))
                $identifiant = $options['identifiant'];
        }
        $this->valide->identifiant = $identifiant;
    }

    public function storeDates() 
    {
        if (!$this->valide->date_saisie) {
           $this->valide->add('date_saisie', date('c'));
        }
        if (!$this->valide->date_signee) {
           $this->valide->add('date_signee', date('c'));
        }
    }
    
    public function getInterpro() 
    {
        if ($this->getEtablissement())
            return $this->getEtablissement()->getInterproObject();
    }

    public function setInterpros() 
    {
        $i = $this->getInterpro();
        if ($i) {
	        $this->interpros->add(0,$i->getKey());
        }
    }
    
    public function hasLastDrmCampagne() {
  		$drmsHistorique = new DRMHistorique($this->identifiant);
  		if ($lastDrm = $drmsHistorique->getLastDRMByCampagne($this->periode)) {
  			if (DRMClient::getInstance()->getMois($lastDrm->periode) == (DRMPaiement::NUM_MOIS_DEBUT_CAMPAGNE - 1)) {
  				if ($lastDrm->isValidee()) {
  					return true;
  				}
  			}
  		}
  		return false;
    }

    public function validate($options = null) 
    {

        if ($next_daids = $this->getSuivante()) {
            $next_daids->precedente = $this->_id;
            $next_daids->save();
        }
        if ($prev_daids = $this->getMother()) {
        	$prev_daids->referente = 0;
            $prev_daids->save();
        }
        $this->storeIdentifiant($options);
        $this->storeDates();
        $this->storeDroits($options);
        $this->setInterpros();
        $this->storeReferente();
    }
    
    
    public function storeReferente() {
        $drm_ref = null;
        if($this->getPreviousVersion()){
            $drm_ref = $this->getMother();
        }
        else
        {
            $id_drm_ref = DRMClient::getInstance()->buildId($this->identifiant, $this->periode);
            if($id_drm_ref != $this->_id){
            $drm_ref = DRMClient::getInstance()->find($id_drm_ref);
            }
        }
        $this->add('referente',1);
        if(!$drm_ref){
            return;
        }
        $drm_ref->add('referente',0);
        $drm_ref->save();
    }

    public function getReferente() {
        if(!$this->exist('referente')){
            return 1;
        }
        return $this->exist('referente');
    }

    public function storeDroits($options) 
    {
        if (!isset($options['no_droits']) || !$options['no_droits']) {
           $this->setDroits();
        }
    }

    public function needNextVersion() 
    {
       return $this->version_document->needNextVersion();      
    }

    public function generateNextVersion() 
    {
        if (!$this->hasVersion()) {
            return $this->version_document->generateRectificativeSuivante();
        }
        return $this->version_document->generateNextVersion();
    }

    public function isRectifiable() 
    {
        return $this->version_document->isRectifiable();
    }

    public function isModifiable() 
    {
        return $this->version_document->isModifiable();
    }

    public function isVersionnable() 
    {
        if (!$this->isValidee()) {
           return false;
        }
        return $this->version_document->isVersionnable();
    }

    public function generateRectificative() 
    {
        return $this->version_document->generateRectificative();
    }

    public function generateModificative() 
    {
        return $this->version_document->generateModificative();
    }

    public function listenerGenerateVersion($document) 
    {
    	if ($prev_daids = $document->getMother()) {
    		if ($prev_daids->hasVersion()) {
            	$document->precedente = $prev_daids->_id;
    		}
        }
        $document->commentaires = null;
        $document->devalide();
    }

    public function getDiffWithMother() 
    {
        return $this->version_document->getDiffWithMother();
    }

    public function getPreviousVersion() 
    {
       return $this->version_document->getPreviousVersion();
    }

    public function findDocumentByVersion($version) 
    {
        return DAIDSClient::getInstance()->find(DAIDSClient::getInstance()->buildId($this->identifiant, $this->periode, $version));
    }

    public function getEuValideDate() 
    {
       return strftime('%d/%m/%Y', strtotime($this->valide->date_signee));
    }
}