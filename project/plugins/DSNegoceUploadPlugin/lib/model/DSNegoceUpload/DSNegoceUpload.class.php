<?php
/**
 * Model for DSNegoce
 *
 */

class DSNegoceUpload extends BaseDSNegoceUpload {


	public function constructId() {
		$numero = DSNegoceUploadClient::getInstance()->getNextIdentifiantForEtablissementAndPeriode($this->identifiant, $this->periode);
		$this->set('_id', DSNegoceUploadClient::TYPE_MODEL.'-' . $this->identifiant . '-' . $this->periode . '-' . $numero);
	}

    public static function isPieceEditable($admin = false) {
    	return false;
    }
}
