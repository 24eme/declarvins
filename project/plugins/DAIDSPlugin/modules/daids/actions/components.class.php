<?php

class daidsComponents extends sfComponents {

    public function executeEtapes() {
        $this->config_certifications = ConfigurationClient::getCurrent()->declaration->certifications;
        $this->certifications = array();
        
        $i = 3;
        foreach ($this->config_certifications as $certification_config) {
            if ($this->daids->exist($certification_config->getHash())) {
            	$certif = $this->daids->get($certification_config->getHash());
	            $this->certifications[$i] = $this->daids->get($certification_config->getHash());
	            $i++;
            }
        }
        $nbCertifs = count($this->certifications);
	    $this->numeros = array(
	            'informations' => 1,
	            'ajouts_liquidations' => 2,
	            'recapitulatif' => 3,
	            'declaratif' => 3 + $nbCertifs,
	            'validation' => 4 + $nbCertifs,
	    ); 
        
        $this->numero = $this->numeros[$this->etape];
        if(isset($this->numeros[$this->daids->etape])) 
            $this->numero_autorise = $this->numeros[$this->daids->etape];
        else 
            $this->numero_autorise = '';
        $this->numero_declaratif = $this->numeros['declaratif'];
        $this->numero_validation = $this->numeros['validation'];

        if ($this->etape == 'recapitulatif') {
            foreach ($this->config_certifications as $certification_config) {
                if ($this->daids->exist($certification_config->getHash())) {
                    if ($this->certification == $certification_config->getKey()) {
                        break;
                    }
                    $this->numero++;
                }
            }
        }
    }

    protected function getNewDRM($identifiant) 
    {
        $daids = DAIDSClient::getInstance()->createDoc($identifiant);
        if($daids && $daids->periode > DAIDSClient::getInstance()->getCurrentPeriode()) {
            $daids = null;
        }
        if ($daids && DAIDSClient::getInstance()->getDAIDSHistorique($identifiant)->hasDAIDSInProcess()) {
            $daids = null;
        }
        if(isset($this->campagne) && $daids->campagne != $this->campagne) {
            $daids = null;
        }
        return $daids;
    }

    public function executeHistoriqueList() 
    {
        $this->daids = array();
        $historique = DAIDSClient::getInstance()->getDAIDSHistorique($this->etablissement->identifiant);
        $this->new_daids = $this->getNewDRM($this->etablissement->identifiant);
        if (!isset($this->campagne) && $this->new_daids) {
            $this->campagne = $this->new_daids->campagne;
        } elseif(!isset($this->campagne)) {
            $campagnes = $historique->getCampagnes();
            $this->campagne = $campagnes[0];
        }
        foreach($historique->getDAIDSsByCampagne($this->campagne) as $key => $d) {
            $this->daids[$key] = DAIDSClient::getInstance()->find($key);
        }

    }

}
