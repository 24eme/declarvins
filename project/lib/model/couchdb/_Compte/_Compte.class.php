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
}