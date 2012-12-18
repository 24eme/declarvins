<?php
/**
 * Model for CompteVirtuel
 *
 */

class CompteVirtuel extends BaseCompteVirtuel {
    protected $gerant_interpro = null;
    const VIEW_KEY_INTERPRO = 0;
    const VIEW_KEY_TYPE = 1;
    const VIEW_KEY_NOM = 2;
    const VIEW_KEY_PRENOM = 3;
    const VIEW_KEY_LOGIN = 4;
    const VIEW_KEY_EMAIl = 5;
    const VIEW_KEY_TELEPHONE = 6;
    const VIEW_KEY_STATUT = 7;


    public function getGerantInterpro() {
        if(is_null($this->gerant_interpro)) {
            foreach($this->interpro as $interpro_id => $interpro) {
                $this->gerant_interpro = acCouchdbManager::getClient('Interpro')->find($interpro_id);
                break;
            }
        }

        return $this->gerant_interpro;
    }


    public function __toString() {
        if ($this->prenom) {
            return sprintf('%s. %s', $this->prenom, strtoupper(substr($this->nom, 0, 1)));
        }
        
        return sprintf('%s', $this->nom);
    }
    
    public function isVirtuel() {
    	return true;
    }
}
