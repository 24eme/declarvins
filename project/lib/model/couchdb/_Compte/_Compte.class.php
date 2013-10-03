<?php
abstract class _Compte extends acVinCompte {  
	
    protected $gerant_interpro = null;
    
    public function getGecos() 
    {
      return $this->login.', '.$this->prenom.' '.$this->nom;
    }
	/**
     *
     * @param string $mot_de_passe 
     */
    public function setPasswordSSHA($mot_de_passe) {
        mt_srand((double)microtime()*1000000);
        $salt = pack("CCCC", mt_rand(), mt_rand(), mt_rand(), mt_rand());
        $hash = "{SSHA}" . base64_encode(pack("H*", sha1($mot_de_passe . $salt)) . $salt);
        $this->_set('mot_de_passe', $hash);
    }

    public function __toString() {

      return $this->login;
    }


    public function getGerantInterpro() {
        if(is_null($this->gerant_interpro)) {
            foreach($this->interpro as $interpro_id => $interpro) {
                $this->gerant_interpro = acCouchdbManager::getClient('Interpro')->find($interpro_id);
                break;
            }
        }

        return $this->gerant_interpro;
    }
}