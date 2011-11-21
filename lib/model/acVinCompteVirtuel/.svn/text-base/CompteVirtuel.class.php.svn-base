<?php
class CompteVirtuel extends BaseCompteVirtuel {
    public function getTiers() {
        if (in_array('metteur_en_marche', $this->droits->toArray())) {
            $tiers = new TiersFictif('MetteurEnMarche');
            $tiers->nom = $this->nom;
            $tiers->commune = $this->commune;
            $tiers->code_postal = $this->code_postal;
            $tiers->no_accises = '1';
            return array("MetteurEnMarche" => $tiers) ;
        }
        return false;
    }
    
    public function getNom() {
        return $this->_get('nom');
    }
    
    public function getNoAccises() {
        return '1';
    }
    
    public function getGecos() {
        return $this->getLogin() . ',' . $this->getNoAccises() . ',' . $this->getNom(). ',';
    }
}