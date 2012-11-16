<?php

class DAIDSClient extends acCouchdbClient 
{
    const VALIDE_STATUS_EN_COURS = '';
    const VALIDE_STATUS_VALIDEE_ENATTENTE = 'VALIDEE';
    const VALIDE_STATUS_VALIDEE_ENVOYEE = 'ENVOYEE';
    const VALIDE_STATUS_VALIDEE_RECUE = 'RECUE';
    
    const MODE_DE_SAISIE_PAPIER = 'PAPIER';
    const MODE_DE_SAISIE_DTI = 'DTI';
    const MODE_DE_SAISIE_EDI = 'EDI';
    const MODE_DE_SAISIE_PAPIER_LIBELLE = 'par l\'interprofession (papier)';
    const MODE_DE_SAISIE_DTI_LIBELLE = 'via Declarvins (DTI)';
    const MODE_DE_SAISIE_EDI_LIBELLE = 'via votre logiciel (EDI)';
    
	protected $daids_historiques = array();
	
    public static function getInstance()
    {
      return acCouchdbManager::getClient("DAIDS");
    } 

    public function buildId($identifiant, $periode, $version = null) 
    {
      return 'DAIDS-'.$identifiant.'-'.$this->buildPeriodeAndVersion($periode, $version);
    } 

    public function buildPeriodeAndVersion($periode, $version) 
    {
      if($version) {
        return sprintf('%s-%s', $periode, $version);
      }
      return $periode;
    }

    public function getRectificative($version) 
    {
      return VersionDocument::buildRectificative($version);
    }

    public function getDAIDSHistorique($identifiant) 
    {
      if (!array_key_exists($identifiant, $this->daids_historiques)) {
      	$this->daids_historiques[$identifiant] = new DAIDSHistorique($identifiant);
      }
      return $this->daids_historiques[$identifiant];
    }
    
    public function createDoc($identifiant, $periode = null) {
    	if (!$periode) {
      		$periode = $this->getCurrentPeriode();
      		$last_daids = $this->getDAIDSHistorique($identifiant)->getLastDAIDS();
		    if ($last_daids) {
        		$periode = $this->getPeriodeSuivante($last_daids->periode);
      		}
    	}
    	return $this->createDocByPeriode($identifiant, $periode);
  	}

    public function createDocByPeriode($identifiant, $periode)
    {
       $prev_daids = $this->getDAIDSHistorique($identifiant)->getPreviousDAIDS($periode);
       $next_daids = $this->getDAIDSHistorique($identifiant)->getNextDAIDS($periode);
       if ($prev_daids) {
       	$daids = $prev_daids->generateSuivante($periode);
       } elseif ($next_daids) {
       	$daids = $next_daids->generateSuivante($periode);
       } else {
        $daids = new DAIDS();
        $daids->identifiant = $identifiant;
        $daids->periode = $periode;
        $daids->campagne = $periode;
        $daids->initProduits();
       }
       $daids->mode_de_saisie =  self::MODE_DE_SAISIE_DTI;
       if($this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
        $daids->mode_de_saisie = self::MODE_DE_SAISIE_PAPIER;
       }
       return $daids;
    }


    public function getPeriodeSuivante($periode) 
    {
    	$campagne = explode('-', $periode);      
      	return $this->buildPeriode($campagne[0], $campagne[1]);
    }

    public function buildPeriode($annee1, $annee2) 
    {
      return sprintf("%04d-%04d", $annee1, $annee2);
    }

    public function getCurrentPeriode() 
    {
      if(date('m') == 8 && date('d') >= 10) {
      	return sprintf('%s-%s', date('Y'), date('Y') + 1);
      } else {
      	return sprintf('%s-%s', date('Y') - 1, date('Y'));
      }
    }
    
    public function formatToCompare($periode)
    {
    	return str_replace('-', '', $periode);
    }


    public function findMasterByIdentifiantAndPeriode($identifiant, $periode, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) 
    {
    	$daids = DAIDSAllView::getInstance()->viewByIdentifiantPeriode($identifiant, $periode);
      	foreach($daids as $id => $d) {
        	return $this->find($id, $hydrate);
      	}
      	return null;
    }

    public function findByIdentifiantAndPeriodeAndRectificative($identifiant, $periode, $rectificative, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) 
    {
      $daids = array();
      $rows = DAIDSAllView::getInstance()->viewByIdentifiantPeriodeAndRectificative($identifiant, $periode, $rectificative);
      foreach($rows as $id => $row) {
        $daids[$id] = $this->find($id); 
      }

      return $daids;
    }

    public function getModeDeSaisieLibelle($key) 
    {
      switch ($key) {
        case self::MODE_DE_SAISIE_DTI:
            return self::MODE_DE_SAISIE_DTI_LIBELLE;
            break;
        case self::MODE_DE_SAISIE_EDI:
            return self::MODE_DE_SAISIE_EDI_LIBELLE;
            break;
        case self::MODE_DE_SAISIE_PAPIER:
            return self::MODE_DE_SAISIE_PAPIER_LIBELLE;
            break;
        default:
            return 'NR';
            break;
      }
    }

    public function getMasterVersionOfRectificative($identifiant, $periode, $rectificative) 
    {
      $rows = DAIDSAllView::getInstance()->viewByIdentifiantPeriodeAndRectificative($identifiant, $periode, $rectificative);
      foreach($rows as $id => $d) {
        return $d[DAIDSAllView::KEY_VERSION];
      }
      return null;
    }
    
    public function getUser() 
    {
    	return sfContext::getInstance()->getUser();
    }
}
