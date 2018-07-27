<?php
/**
 * Model for DSNegoce
 *
 */

class DSNegoce extends BaseDSNegoce {


	public function constructId() {
		$this->set('_id', 'DSNEGOCE-' . $this->identifiant . '-' . $this->periode);
	}

    public static function isPieceEditable($admin = false) {
    	return ($admin)? true : false;
    }
}