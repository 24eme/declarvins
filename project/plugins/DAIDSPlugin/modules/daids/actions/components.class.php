<?php

class daidsComponents extends sfComponents 
{

    public function executeEtapes() 
    {
        $this->config_certifications = ConfigurationClient::getCurrent()->getCertifications();
        $this->certifications = array();
        
        $i = 2;
        foreach ($this->config_certifications as $certification_config) {
            if ($this->daids->declaration->certifications->exist($certification_config)) {
            	$certif = $this->daids->declaration->certifications->get($certification_config);
	            $this->certifications[$i] = $certif;
	            $i++;
            }
        }
        $nbCertifs = count($this->certifications);
	    $this->numeros = array(
	            'informations' => 1,
	            'recapitulatif' => 2,
	            'validation' => 2 + $nbCertifs,
	    ); 
        
        $this->numero = $this->numeros[$this->etape];
        if(isset($this->numeros[$this->daids->etape])) 
            $this->numero_autorise = $this->numeros[$this->daids->etape];
        else 
            $this->numero_autorise = '';
        $this->numero_validation = $this->numeros['validation'];

        if ($this->etape == 'recapitulatif') {
            foreach ($this->config_certifications as $certification_config) {
            	if ($this->daids->declaration->certifications->exist($certification_config)) {
            		$certif = $this->daids->declaration->certifications->get($certification_config);
                    if ($this->certification == $certif->getKey()) {
                        break;
                    }
                    $this->numero++;
                }
            }
        }
    }

    protected function getNewDAIDS($identifiant) 
    {
        $daids = DAIDSClient::getInstance()->createDoc($identifiant);
        if (!$daids) {
        	return null;
        }
        if (count($daids->getDetails()) == 0) {
        	$daids = null;
        }
        if($daids && $daids->periode > DAIDSClient::getInstance()->getCurrentPeriode()) {
            $daids = null;
        }
        if ($daids && DAIDSClient::getInstance()->getDAIDSHistorique($identifiant)->hasDAIDSInProcess()) {
            $daids = null;
        }
        if(isset($this->campagne) && $daids && $daids->campagne != $this->campagne) {
            $daids = null;
        }
        return $daids;
    }

    public function executeHistoriqueList() 
    {
        $this->daids = array();
        $historique = DAIDSClient::getInstance()->getDAIDSHistorique($this->etablissement->identifiant);
        $this->new_daids = ($this->etablissement->statut != Etablissement::STATUT_ARCHIVE)? $this->getNewDAIDS($this->etablissement->identifiant) : null;
        foreach($historique->getDAIDSs() as $key => $d) {
        	if ($daids = DAIDSClient::getInstance()->find($d->_id)) {
            	$this->daids[$key] = $daids;
        	}
        }

    }

}
