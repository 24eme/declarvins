<?php
/**
 * Model for DRMPaiement
 *
 */

class DRMPaiement extends BaseDRMPaiement {
  const FREQUENCE_ANNUELLE = 'Annuelle';
  const FREQUENCE_MENSUELLE = 'Mensuelle';
  
  public function isAnnuelle() {
  	return ($this->frequence == self::FREQUENCE_ANNUELLE)? true : false;
  }

}