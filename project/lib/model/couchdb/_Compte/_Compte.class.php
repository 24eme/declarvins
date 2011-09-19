<?php
abstract class _Compte extends Base_Compte {
    const STATUT_NOUVEAU = 'NOUVEAU';
    const STATUT_ACTIVE = 'ACTIVE';
    
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

    public function save($conn = null) {
      if ($this->statut != _Compte::STATUT_ACTIVE) {
	$active = true;
	foreach ($this->interpro as $inter) {
	  if ($inter->getStatut() == _Compte::STATUT_VALIDATION_ATTENTE) {
	    $active = false;
	    break;
	  }
	}
	if ($active)
	  $this->setStatut(_Compte::STATUT_ACTIVE);
      }
      return parent::save($conn);
    }
    
}