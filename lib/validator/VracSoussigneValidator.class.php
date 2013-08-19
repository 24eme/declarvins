<?php

class VracSoussigneValidator extends sfValidatorBase {

	public function configure($options = array(), $messages = array()) {
        $this->addMessage('impossible_acheteur_vendeur', "Le vendeur et l'acheteur ne peuvent être la même personne");
    }
    
    protected function doClean($values) {
    	if (isset($values['vous_etes']) && $values['vous_etes'] == VracClient::VRAC_TYPE_VENDEUR) {
    		if (!$values ['acheteur_identifiant']) {
    			throw new sfValidatorErrorSchema($this, array('acheteur_identifiant' => new sfValidatorError($this, 'required')));
    		}
    	} elseif (isset($values['vous_etes']) && $values['vous_etes'] == VracClient::VRAC_TYPE_ACHETEUR) {
    		if (!$values ['vendeur_identifiant']) {
    			throw new sfValidatorErrorSchema($this, array('vendeur_identifiant' => new sfValidatorError($this, 'required')));
    		}
    	}
    	if (!isset($values['vous_etes'])) {
    		if (!$values ['acheteur_identifiant'] && $values ['vendeur_identifiant']) {
    			throw new sfValidatorErrorSchema($this, array('acheteur_identifiant' => new sfValidatorError($this, 'required')));
    		}
    		if ($values ['acheteur_identifiant'] && !$values ['vendeur_identifiant']) {
    			throw new sfValidatorErrorSchema($this, array('vendeur_identifiant' => new sfValidatorError($this, 'required')));
    		}
    		if (!$values ['acheteur_identifiant'] && !$values ['vendeur_identifiant']) {
    			$errors = array('vendeur_identifiant' => new sfValidatorError($this, 'required'), 'acheteur_identifiant' => new sfValidatorError($this, 'required'));
    			throw new sfValidatorErrorSchema($this, $errors);
    		}
    	}
    	if (isset($values['vendeur_identifiant']) && isset($values['acheteur_identifiant']) && $values ['vendeur_identifiant'] == $values ['acheteur_identifiant']) {
    		throw new sfValidatorErrorSchema($this, array(new sfValidatorError($this, 'impossible_acheteur_vendeur')));
    	}
    	if (isset($values['vous_etes_identifiant']) && !(isset($values['vendeur_identifiant']) && isset($values['acheteur_identifiant']))) {
    		if ((isset($values['vendeur_identifiant']) && $values ['vendeur_identifiant'] == $values ['vous_etes_identifiant']) || (isset($values['acheteur_identifiant']) && $values['vous_etes_identifiant'] == $values ['acheteur_identifiant'])) {
    			throw new sfValidatorErrorSchema($this, array(new sfValidatorError($this, 'impossible_acheteur_vendeur')));
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
