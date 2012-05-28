<?php
class Interpro extends BaseInterpro {
	const INTER_RHONE_ID = 'inter-rhone';
	const CIVP_ID = 'civp';
	const INTERVINS_SUD_EST_ID = 'intervins-sud-est';
  public function __toString() {
    return $this->nom;
  }
  public function getKey() {
    return $this->_id;
  }
}