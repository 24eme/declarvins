<?php

class DAIDSAllView extends acCouchdbView
{

    const KEY_INDEX_ETABLISSEMENT = 0;
    const KEY_CAMPAGNE = 1;
    const KEY_PERIODE = 2;
    const KEY_VERSION = 3;
    const KEY_MODE_DE_DAISIE = 4;
    const KEY_STATUT = 5;
    const KEY_STATUT_DOUANE_ENVOI = 6;
    const KEY_STATUT_DOUANE_ACCUSE = 7;

    public static function getInstance() 
    {
        return acCouchdbManager::getView('daids', 'all', 'DAIDS');
    }

    public function findByIdentifiant($identifiant) 
    {
    	return $this->client->startkey(array($identifiant))
                    		->endkey(array($identifiant, array()))
                    		->reduce(false)
                    		->getView($this->design, $this->view);
    }

    public function findByIdentifiantPeriode($identifiant, $periode) 
    {
    	return $this->client->startkey(array($identifiant, $periode, $periode))
                    		->endkey(array($identifiant, $periode, $periode, array()))
                    		->reduce(false)
                    		->getView($this->design, $this->view);
    }

    public function findByIdentifiantPeriodeAndVersion($identifiant, $periode, $version_rectificative) 
    {
    	return $this->client->startkey(array($identifiant, $periode, $periode, $version_rectificative))
                    		->endkey(array($identifiant, $periode, $periode, $this->buildVersion($version_rectificative, 99), array()))
                    		->reduce(false)
                    		->getView($this->design, $this->view);
    }

    public function viewByIdentifiant($identifiant) 
    {
      $rows = $this->findByIdentifiant($identifiant);      
      $daids = array();
      foreach($rows->rows as $row) {
        $daids[$row->id] = $row->key;
      }
      krsort($daids);
      return $daids;
    }

    public function viewByIdentifiantPeriode($identifiant, $periode) 
    {
      $rows = $this->findByIdentifiantPeriode($identifiant, $periode);
      $daids = array();
      foreach($rows->rows as $row) {
        $daids[$row->id] = $row->key;
      }
      krsort($daids);
      return $daids;
    }

    public function viewByIdentifiantPeriodeAndVersion($identifiant, $periode, $version_rectificative) {
      $campagne = $this->buildCampagne($periode);
	  $rows = $this->findByIdentifiantPeriode($identifiant, $periode, $version_rectificative);
      $daids = array();
      foreach($rows->rows as $row) {
        $daids[$row->id] = $row->key;
      }
      krsort($daids);
      return $daids;
    }

    public function buildVersion($rectificative, $modificative) 
    {
      return DAIDS::buildVersion($rectificative, $modificative);
    }

}  