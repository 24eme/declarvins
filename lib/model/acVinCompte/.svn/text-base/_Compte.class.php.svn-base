<?php
abstract class _Compte extends Base_Compte {
    const STATUS_NOUVEAU = 'NOUVEAU';
    const STATUS_INSCRIT = 'INSCRIT';
    const STATUS_MOT_DE_PASSE_OUBLIE = 'MOT_DE_PASSE_OUBLIE';
    
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
    
    /**
     * 
     */
    protected function updateStatut() {
       if (substr($this->mot_de_passe,0,6) == '{SSHA}') {
           $this->_set('statut', self::STATUS_INSCRIT);
       } elseif(substr($this->mot_de_passe,0,6) == '{TEXT}') {
           $this->_set('statut', self::STATUS_NOUVEAU);
       } elseif(substr($this->mot_de_passe,0,8) == '{OUBLIE}') {
           $this->_set('statut', self::STATUS_MOT_DE_PASSE_OUBLIE);
       } else {
           $this->_set('statut', null);
       }
    }
    
    /**
     *
     * @return string 
     */
    public function getStatus() {
        $this->updateStatut();
        return $this->_get('statut');
    }
    
    /**
     * 
     */
    public function setStatus() {
        throw new sfException("Compte status is not editable");
    }
    
    /**
     *
     * @return string 
     */
    public function getNom() {
        return ' ';
    }
    
    /**
     *
     * @return string 
     */
    public function getGecos() {
        return ',,'.$this->getNom().',';
    }
    
    /**
     *
     * @return string 
     */
    public function getAdresse() {
        return ' ';
    }
    
    /**
     *
     * @return string 
     */
    public function getCodePostal() {
        return ' ';
    }
    
    /**
     *
     * @return string 
     */
    public function getCommune() {
        return  ' ';
    }
    
    /**
     * 
     */
    public function updateLdap() {
        if ($this->getStatus() == self::STATUS_INSCRIT) {
            $ldap = new Ldap();
            if($ldap->exist($this)) {
                $ldap->update($this);
            }else {
                $ldap->add($this);
            }
        }
    }
    
    /**
     * 
     */
    public function save() {
        $this->updateStatut();
        $this->updateLdap();
        parent::save();
    }
    
    
    
}
