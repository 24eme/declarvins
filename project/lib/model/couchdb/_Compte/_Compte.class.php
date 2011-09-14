<?php
abstract class _Compte extends Base_Compte {
    const STATUS_NOUVEAU = 'NOUVEAU';
    const STATUS_INSCRIT = 'INSCRIT';
    
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
    
}