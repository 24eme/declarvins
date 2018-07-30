<?php
/**
 * Model for DSNegoce
 *
 */

class DSNegoce extends BaseDSNegoce {


	public function constructId() {
		$numero = DSNegoceClient::getInstance()->getNextIdentifiantForEtablissementAndDate($this->identifiant, $this->date_depot);
		$this->set('_id', 'DSNEGOCE-' . $this->identifiant . '-' . $this->periode . '-' . $numero);
	}

    public static function isPieceEditable($admin = false) {
    	return ($admin)? true : false;
    }
}