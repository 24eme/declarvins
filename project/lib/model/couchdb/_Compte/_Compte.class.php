<?php
abstract class _Compte extends acVinCompte {  
	
	const STATUT_ACTIVE = 'ACTIVE';
	const STATUT_INACTIVE = 'INACTIVE';
    const STATUT_VALIDATION_ATTENTE = "ATTENTE";
    const STATUT_VALIDATION_VALIDE = "VALIDE";
    
    public function getGecos() 
    {
      return $this->login.', '.$this->prenom.' '.$this->nom;
    }
    
    public function getNbEtablissementByInterproId() 
    {
        $result = array();
        foreach ($this->getTiers() as $tier) {
            if (array_key_exists($tier->getInterpro(), $result)) {
                $result[$tier->getInterpro()] = $result[$tier->getInterpro()] + 1;
            }
            else {
                $result[$tier->getInterpro()] = 1;
            }
        }
        return $result;
    }
}