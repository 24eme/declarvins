<?php
abstract class _Compte extends Base_Compte {
    const STATUS_NOUVEAU = 'NOUVEAU';
    const STATUS_INSCRIT = 'INSCRIT';
    
    const STATUT_VALIDATION_ATTENTE = "ATTENTE";
    const STATUT_VALIDATION_VALIDE = "VALIDE";
    
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
    public function getNbChaiByInterproId() {
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