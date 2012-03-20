<?php

/**
 * Model for DRM
 *
 */
class DRM extends BaseDRM {

    const NOEUD_TEMPORAIRE = 'TMP';
    const DEFAULT_KEY = 'DEFAUT';

    public function constructId() {
        $rectificative = ($this->exist('rectificative')) ? $this->rectificative : null;
        $this->set('_id', DRMClient::getInstance()->getId($this->identifiant, $this->campagne, $rectificative));
    }

    public function getCampagneAndRectificative() {
        $rectificative = ($this->exist('rectificative')) ? $this->rectificative : null;

        return DRMClient::getInstance()->getCampagneAndRectificative($this->campagne, $rectificative);
    }

    public function synchroniseDeclaration() {
        foreach ($this->produits as $certification) {
            foreach ($certification as $appellation) {
            	foreach ($appellation as $item) {
                	$item->updateDetail();
            	}
            }
        }
    }

    public function getProduit($hash, $labels = array()) {
      $hashes = $this->interpretHash($hash);
      sort($labels);
      try {
    	if ($produits = $this->getProduits()->get($hashes['certification'])->get($hashes['appellation'])) {
    	  foreach ($produits as $p) {
    	    $leslabels = $p->label->toArray();
    	    sort($leslabels);
    	    if (!count(array_diff($leslabels,$labels)) &&  $p->hashref == $hash) {
    	      return $p;
    	    }
    	  }
    	}
      }catch(Exception $e) {
      }
      return false;
    }

    public function addProduit($hash, $labels = array()) {
      if ($p = $this->getProduit($hash, $labels)) {
	return $p;
      }
      $hashes = $this->interpretHash($hash);
      $produit = $this->produits->add($hashes['certification'])->add($hashes['appellation'])->add();
      $produit->setLabel($labels);
      $produit->setHashref($hash);

      $this->synchroniseDeclaration();
      
      return $produit;

    }

    public function getDetailsAvecVrac() {
        $details = array();
        foreach ($this->declaration->certifications as $certifications) {
            foreach ($certifications->appellations as $appellation) {
                foreach ($appellation->lieux as $lieu) {
                    foreach ($lieu->couleurs as $couleur) {
                    	foreach ($couleur->cepages as $cepage) {
    	                    foreach ($cepage->millesimes as $millesime) {
                                foreach ($millesime->details as $detail) {
        	                        if ($detail->sorties->vrac) {
        	                            $details[] = $detail;
        	                        }
                                }
    	                    }
                    	}
                    }
                }
            }
        }
        return $details;
    }

    public function generateSuivante($campagne) 
    {
        $drm_suivante = clone $this;
    	$drm_suivante->init();
        $drm_suivante->update();
        $drm_suivante->remove('rectificative');
        $drm_suivante->campagne = $campagne;
        $drm_suivante->remove('douane');
        $drm_suivante->add('douane');
        $drm_suivante->remove('declarant');
        $drm_suivante->add('declarant');
        $drm_suivante->valide = 0;

        return $drm_suivante;
    }
    
    public function getNextCertification($currentCertification)
    {
    	$findCertification = false;
    	$nextCertification = null;
    	$config_certifications = ConfigurationClient::getCurrent()->declaration->certifications;
    	foreach ($config_certifications as $certification => $produit) {
            if ($this->produits->exist($certification)) {
            	if ($findCertification) {
            		$nextCertification = $this->declaration->certifications->get($certification);
            		break;
            	}
                if ($certification == $currentCertification->getKey()) {
                	$findCertification = true;
                }
            }
        }
        return $nextCertification;
    }

    public function setCampagneMoisAnnee($mois, $annee) {
      $this->campagne = sprintf("%04d-%02d", $annee, $mois);
    }

    public function setMois($mois) {
      $annee = $this->getAnnee();
      $this->setCampagneMoisAnnee($mois, $annee);
    }

    public function setAnnee($annee) {
      $mois = $this->getMois();
      $this->setCampagneMoisAnnee($mois, $annee);
    }

    public function getMois() {
      return preg_replace('/.*-/', '', $this->campagne)*1;
    }

    public function getAnnee() {
      return preg_replace('/-.*/', '', $this->campagne)*1;
    }
    
    public function setDroits() {
      $this->remove('droits');
      $this->add('droits');
      foreach ($this->declaration->certifications as $certification) {
	foreach ($certification->appellations as $appellation) {
	  $appellation->updateDroits($this->droits);
	}
      }
    }

    public function getEtablissement() {
    	
        return EtablissementClient::getInstance()->retrieveById($this->identifiant);
    }
    
    public function getInterpro() {
    	
        return $this->getEtablissement()->getInterproObject();
    }
    
    public function getDrmHistorique() {

        return $this->store('drm_historique', array($this, 'getDrmHistoriqueAbstract'));
    }

    public function isRectificative() {

        return $this->exist('rectificative') && $this->rectificative > 0;
    }

    public function isRectificable() {
        if (!$this->valide) {

            return false;
        }

        if ($drm = DRMClient::getInstance()->findLastByIdentifiantAndCampagne($this->identifiant, $this->campagne, acCouchdbClient::HYDRATE_JSON)) {

            return $drm->_id == $this->get('_id');
        }

        return false;
    }

    public function generateRectificative() {
        $drm_rectificative = clone $this;
        $drm_rectificative->valide = 0;

        if(!$this->isRectificable()) {

            throw new sfException('This DRM is not rectificable, maybe she was already rectificate');
        }

        if(!$drm_rectificative->exist('rectificative')) {
            $drm_rectificative->add('rectificative', 0);
        }

        $drm_rectificative->rectificative += 1;

        return $drm_rectificative;
    }

    public function getSuivante() {
       $date_campagne = new DateTime($this->getAnnee().'-'.$this->getMois().'-01');
       $date_campagne->modify('+1 month');
       $next_campagne = DRMClient::getInstance()->getCampagne($date_campagne->format('Y'), $date_campagne->format('m'));

       $next_drm = DRMClient::getInstance()->findLastByIdentifiantAndCampagne($this->identifiant, $next_campagne);

       return $next_drm;
    }

    public function generateRectificativeSuivante() {
        if (!$this->isRectificative()) {

            throw new sfException('This drm is not a rectificative');
        }

        $next_drm = $this->getSuivante();

        if ($next_drm) {
            $next_drm_rectificative = $next_drm->generateRectificative();
            foreach($this->getDiffWithMasterDRM() as $key => $value) {
                $this->replicateDetail($next_drm_rectificative, $key, $value, 'total', 'total_debut_mois');
                $this->replicateDetail($next_drm_rectificative, $key, $value, 'stocks_fin/bloque', 'stocks_debut/bloque');
                $this->replicateDetail($next_drm_rectificative, $key, $value, 'stocks_fin/warrante', 'stocks_debut/warrante');
                $this->replicateDetail($next_drm_rectificative, $key, $value, 'stocks_fin/instance', 'stocks_debut/instance');
                $this->replicateDetail($next_drm_rectificative, $key, $value, 'stocks_fin/commercialisable', 'stocks_debut/commercialisable');
            }
            $next_drm_rectificative->update();

            return $next_drm_rectificative;
        } else {
            return null;
        }
    }

    protected function replicateDetail(&$drm, $key, $value, $hash_match, $hash_replication) {
        if (preg_match('|^(/declaration/certifications/.+/appellations/.+/lieux/.+/couleurs/.+/cepages/.+/millesimes/.+/details/.+)/'.$hash_match.'$|', $key, $match)) {
            $detail = $this->get($match[1]);
            if (!$drm->exist($detail->getHash())) {
                $drm->addProduit($detail->getMillesime()->getHash(), $detail->label->toArray());
            }
            $drm->get($detail->getHash())->set($hash_replication, $value);
        }

        return $drm;
    }

    public function getDRMMaster($hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        if (!$this->isRectificative()) {

            throw new sfException("You can not recover the master of a non rectificative drm");
        }

        return DRMClient::getInstance()->findByIdentifiantCampagneAndRectificative($this->identifiant, $this->campagne, $this->rectificative - 1, $hydrate);    
    }

    public function getDiffWithMasterDRM() {

        return $this->store('diff_with_master_drm', array($this, 'getDiffWithMasterDRMAbstract'));
    }

    public function isModifiedMasterDRM($hash_or_object, $key = null) {
        if(!$this->isRectificative()) {

            return false;
        }
        $hash = ($hash_or_object instanceof acCouhdbJson) ? $hash_or_object->getHash() : $hash_or_object;
        $hash .= ($key) ? "/".$key : null;

        return array_key_exists($hash, $this->getDiffWithMasterDRM());
    }

    public function validate() {
        $this->valide = 1;
        $this->setDroits();
    }

    public function save() {
        if (!preg_match('/^2\d{3}-[01][0-9]$/', $this->campagne)) {
	    
            throw new sfException('Wrong format for campagne ('.$this->campagne.')');
        }

        return parent::save();
    }

    protected function getDiffWithAnotherDRM(stdClass $drm) {
        $other_json = new acCouchdbJsonNative($drm);
        $current_json = new acCouchdbJsonNative($this->getData());

        return $current_json->diff($other_json);
    }

    protected function getDiffWithMasterDRMAbstract() {
        $drm_master = $this->getDRMMaster(acCouchdbClient::HYDRATE_JSON)->getData();

        return $this->getDiffWithAnotherDRM($drm_master);
    }

    protected function getDrmHistoriqueAbstract() {
        return new DRMHistorique($this->identifiant, $this->campagne);
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
        $configurationDroits = $appellation->getConfig()->interpro->get($this->getInterpro()->get('_id'))->droits->get($type)->getCurrentDroit($this->campagne);
        $droit = $appellation->droits->get($type);
        $droit->ratio = $configurationDroits->ratio;
        $droit->code = $configurationDroits->code;
    }
}