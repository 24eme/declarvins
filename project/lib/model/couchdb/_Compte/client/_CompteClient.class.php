<?php

class _CompteClient extends acVinCompteClient 
{        

    private $droits = array(acVinCompteSecurityUser::CREDENTIAL_OPERATEUR => 'Opérateur',
                            acVinCompteSecurityUser::CREDENTIAL_ADMIN => 'Administrateur');

    private $acces = array(acVinCompteSecurityUser::CREDENTIAL_ACCES_PLATERFORME => 'Declarvins',
                            acVinCompteSecurityUser::CREDENTIAL_ACCES_EDI_DRM => 'EDI DRM',
                            acVinCompteSecurityUser::CREDENTIAL_ACCES_EDI_VRAC => 'EDI Vrac',
                            acVinCompteSecurityUser::CREDENTIAL_ACCES_EDI_TRANSACTION => 'EDI Transaction',
                            acVinCompteSecurityUser::CREDENTIAL_ACCES_EDI_DAIDS => 'EDI DAIDS');
                            
     
    /**
     *
     * @return _CompteClient 
     */
    public static function getInstance() {
        
        return acCouchdbManager::getClient("_COMPTE");
    }
    
    
    public function getDroits()
    {
        
        return $this->droits;
    }
    
    
    public function getAcces()
    {
        
        return $this->acces;
    }
}
