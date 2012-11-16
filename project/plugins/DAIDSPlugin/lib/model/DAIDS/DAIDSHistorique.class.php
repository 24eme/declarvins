<?php

class DAIDSHistorique {

    protected $identifiant = null;
    protected $daids = null;
    protected $has_daids_process = null;

    public function __construct($identifiant)
    {
        $this->identifiant = $identifiant;
        $this->loadDAIDSs();
    }

    public function hasDAIDSInProcess() 
    {
        return $this->has_daids_process;
    }

    public function getLastDAIDS() 
    {
        foreach($this->daids as $daids) {
            return DAIDSClient::getInstance()->find($daids->_id);
        }
    }

    public function getPreviousDAIDS($periode) 
    {
        foreach($this->daids as $daids) {
            if ($daids->periode < $periode) {
                return DAIDSClient::getInstance()->find($daids->_id);
            }
        }
    }

    public function getNextDAIDS($periode) 
    {
        $next_daids = new stdClass();
        $next_daids->_id = null;
        $next_daids->periode = '9999-9999';
        foreach($this->daids as $daids) {
            if (DAIDSClient::getInstance()->formatToCompare($daids->periode) < DAIDSClient::getInstance()->formatToCompare($next_daids->periode)) {
                $next_daids = $daids;
            }
            if($daids->periode <= $periode) {
                break;
            }
        }
        if(!$next_daids->_id) {
            return null;
        }
        return DAIDSClient::getInstance()->find($next_daids->_id);
    }

    protected function loadDAIDSs() 
    {
        $this->daids = array();
        $daids = DAIDSAllView::getInstance()->viewByIdentifiant($this->identifiant);
        $this->has_daids_process = false;
        foreach($daids as $d) {
            $key = $d[DAIDSAllView::KEY_PERIODE].DAIDSClient::getInstance()->getRectificative($d[DAIDSAllView::KEY_VERSION]);
            if (array_key_exists($key, $this->daids)) {
                continue;
            }
            $this->daids[$key] = $this->build($d);
            if (!$this->daids[$key]->valide->date_saisie) {
                $this->has_daids_process = true;
            }
        }
    }

    protected function build($ligne) 
    {
        $daids = new stdClass();
        $daids->identifiant = $ligne[DAIDSAllView::KEY_INDEX_ETABLISSEMENT];
        $daids->campagne = $ligne[DAIDSAllView::KEY_CAMPAGNE];
        $daids->periode = $ligne[DAIDSAllView::KEY_PERIODE];
        $daids->version = $ligne[DAIDSAllView::KEY_VERSION];
        $daids->mode_de_saisie = $ligne[DAIDSAllView::KEY_MODE_DE_DAISIE];
        $daids->valide = new stdClass();
        $daids->valide->date_saisie = $ligne[DAIDSAllView::KEY_STATUT];
        $daids->douane = new stdClass();
        $daids->douane->envoi = $ligne[DAIDSAllView::KEY_STATUT_DOUANE_ENVOI];
        $daids->douane->accuse = $ligne[DAIDSAllView::KEY_STATUT_DOUANE_ACCUSE];
        $daids->_id = DAIDSClient::getInstance()->buildId($daids->identifiant, $daids->periode, $daids->version);
        return $daids;
    }
    
    public function getDAIDSs() {
        return $this->daids;
    }

    public function getIdentifiant() 
    {
        return $this->identifiant;
    }

    public function getCampagnes() {
        $campagnes = array();
        foreach($this->getDAIDSs() as $d) {
            if (!in_array($d->campagne, $campagnes)) {
                $campagnes[] = $d->campagne;
            }
        }
        return $campagnes;
    }

    public function getDAIDSsByCampagne($campagne) {
        $daids = array();
        foreach($this->getDAIDSs() as $d) {
            if ($d->campagne == $campagne) {
                $daids[$d->_id] = $d;
            }
        }
        return $daids;
    }
}

