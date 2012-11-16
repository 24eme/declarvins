<?php

class daidsComponents extends sfComponents 
{

    public function executeEtapes() 
    {
        $this->config_certifications = ConfigurationClient::getCurrent()->declaration->certifications;
        $this->certifications = array();
        
        $i = 2;
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
                if ($this->daids->exist($certification_config->getHash())) {
                    if ($this->certification == $certification_config->getKey()) {
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
        $this->new_daids = $this->getNewDAIDS($this->etablissement->identifiant);
        foreach($historique->getDAIDSs() as $key => $d) {
            $this->daids[$key] = DAIDSClient::getInstance()->find($d->_id);
        }

    }

}
