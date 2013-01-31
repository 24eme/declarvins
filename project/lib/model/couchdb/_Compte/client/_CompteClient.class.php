<?php

class _CompteClient extends acVinCompteClient 
{        

    const KEY_INTERPRO = 0;
    const KEY_TYPE = 1;
    const KEY_NOM = 2;
    const KEY_PRENOM = 3;
    const KEY_LOGIN = 4;
    const KEY_EMAIL = 5;
    const KEY_TELEPHONE = 6;

    private $droits = array(acVinCompteSecurityUser::CREDENTIAL_OPERATEUR => 'Opérateur',
                            acVinCompteSecurityUser::CREDENTIAL_ADMIN => 'Administrateur');
     
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
    
    public function findAll()
    {

    	  return $this->getView('compte', 'all');
    }

    public function findAllByInterpo($interpro_id)
    {

        return $this->startkey(array($interpro_id))
                    ->endkey(array($interpro_id, array()))
                    ->getView('compte', 'all');
    }

    public function findAllOperateurByInterpo($interpro_id)
    {

        return $this->startkey(array($interpro_id, 'CompteVirtuel'))
                    ->endkey(array($interpro_id, 'CompteVirtuel', array()))
                    ->getView('compte', 'all');
    }

    public function findAllPartenaireByInterpo($interpro_id)
    {

        return $this->startkey(array($interpro_id, 'ComptePartenaire'))
                    ->endkey(array($interpro_id, 'ComptePartenaire', array()))
                    ->getView('compte', 'all');
    }

  	public function makeLibelle($datas) {
        $compteLibelle = '';
        if ($nom = $datas[self::KEY_NOM]) {
            $compteLibelle .= $nom.' ';
        }
        if ($prenom = $datas[self::KEY_PRENOM]) {
            $compteLibelle .= $prenom;
        }
        $compteLibelle .= ' ('.$datas[self::KEY_LOGIN];
        if ($email = $datas[self::KEY_EMAIL]) {
            if ($compteLibelle && $datas[self::KEY_LOGIN]) {
                $compteLibelle .= ' / ';
            }
            $compteLibelle .= $email;
        }
        if ($telephone = $datas[self::KEY_TELEPHONE]) {
            if ($compteLibelle) {
                $compteLibelle .= ' / ';
            }
            $compteLibelle .= $telephone;
        }
        $compteLibelle .= ') ';
        
        return trim($compteLibelle);
    }
}
