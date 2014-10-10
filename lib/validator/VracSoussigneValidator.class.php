<?php

class VracSoussigneValidator extends sfValidatorBase {

	public function configure($options = array(), $messages = array()) {
        $this->addMessage('impossible_acheteur_vendeur', "Le vendeur et l'acheteur ne peuvent être le même établissement");
    }
    
    protected function doClean($values) {
    	$errorSchema = new sfValidatorErrorSchema($this);
    	$hasError = false;
    	if (isset($values['vous_etes']) && $values['vous_etes'] == VracClient::VRAC_TYPE_VENDEUR) {
    		if (!$values ['acheteur_identifiant']) {
    					$errorSchema->addError(new sfValidatorError($this, 'required'), 'acheteur_identifiant');
    					$hasError = true;
    		}
    	} elseif (isset($values['vous_etes']) && $values['vous_etes'] == VracClient::VRAC_TYPE_ACHETEUR) {
    		if (!$values ['vendeur_identifiant']) {
    					$errorSchema->addError(new sfValidatorError($this, 'required'), 'vendeur_identifiant');
    					$hasError = true;
    		}
    	}
    	if (!isset($values['vous_etes'])) {
    		if (!$values ['acheteur_identifiant'] && $values ['vendeur_identifiant']) {
    					$errorSchema->addError(new sfValidatorError($this, 'required'), 'acheteur_identifiant');
    					$hasError = true;
    		}
    		if ($values ['acheteur_identifiant'] && !$values ['vendeur_identifiant']) {
    					$errorSchema->addError(new sfValidatorError($this, 'required'), 'vendeur_identifiant');
    					$hasError = true;
    		}
    		if (!$values ['acheteur_identifiant'] && !$values ['vendeur_identifiant']) {
    			$errors = array('vendeur_identifiant' => new sfValidatorError($this, 'required'), 'acheteur_identifiant' => new sfValidatorError($this, 'required'));
    					$errorSchema->addError(new sfValidatorError($this, 'required'), 'vendeur_identifiant');
    					$errorSchema->addError(new sfValidatorError($this, 'required'), 'acheteur_identifiant');
    					$hasError = true;
    		}
    	}
    	if (isset($values['vendeur_identifiant']) && isset($values['acheteur_identifiant']) && $values ['vendeur_identifiant'] == $values ['acheteur_identifiant']) {
    					$errorSchema->addError(new sfValidatorError($this, 'impossible_acheteur_vendeur'));
    					$hasError = true;
    	}
    	if (isset($values['vous_etes_identifiant']) && isset($values['acheteur_identifiant']) && isset($values['vous_etes']) && $values['vous_etes'] != VracClient::VRAC_TYPE_ACHETEUR && $values ['vous_etes_identifiant'] == $values ['acheteur_identifiant']) {
    					$errorSchema->addError(new sfValidatorError($this, 'impossible_acheteur_vendeur'));
    					$hasError = true;
    	}
    	if (isset($values['vous_etes_identifiant']) && isset($values['vendeur_identifiant']) && isset($values['vous_etes']) && $values['vous_etes'] != VracClient::VRAC_TYPE_VENDEUR && $values ['vous_etes_identifiant'] == $values ['vendeur_identifiant']) {
    					$errorSchema->addError(new sfValidatorError($this, 'impossible_acheteur_vendeur'));
    					$hasError = true;
    	}
    	if (isset($values['mandataire_exist']) && $values['mandataire_exist']) {
    		if (!$values['mandataire_identifiant']) {
    					$errorSchema->addError(new sfValidatorError($this, 'required'), 'mandataire_identifiant');
    					$hasError = true;
    		}
    	}
    	if ($hasError) {
    		throw new sfValidatorErrorSchema($this, $errorSchema);
    	}
        return $values;
    }

}
