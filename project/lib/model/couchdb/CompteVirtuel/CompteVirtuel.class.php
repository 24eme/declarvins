<?php
/**
 * Model for CompteVirtuel
 *
 */

class CompteVirtuel extends BaseCompteVirtuel {
    protected $gerant_interpro = null;


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
}
