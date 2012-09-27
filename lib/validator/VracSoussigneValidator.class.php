<?php

class VracSoussigneValidator extends sfValidatorBase {

    
    protected function doClean($values) {
    	if ($values['vous_etes'] == VracClient::VRAC_TYPE_VENDEUR) {
    		if (!$values ['acheteur_identifiant']) {
    			throw new sfValidatorErrorSchema($this, array('acheteur_identifiant' => new sfValidatorError($this, 'required')));
    		}
    	} elseif ($values['vous_etes'] == VracClient::VRAC_TYPE_ACHETEUR) {
    		if (!$values ['vendeur_identifiant']) {
    			throw new sfValidatorErrorSchema($this, array('vendeur_identifiant' => new sfValidatorError($this, 'required')));
    		}
    	}
    	if ($values['mandataire_exist']) {
    		if (!$values['mandataire_identifiant']) {
    			throw new sfValidatorErrorSchema($this, array('mandataire_identifiant' => new sfValidatorError($this, 'required')));
    		}
    	}
        return $values;
    }

}
