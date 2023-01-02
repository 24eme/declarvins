<?php

class VracConditionValidator extends sfValidatorBase {

    public function configure($options = array(), $messages = array()) {
        $this->addMessage('impossible_volume', "La somme des volumes ne correspond pas au volume total proposé");
        $this->addMessage('impossible_date', "La date limite doit être supérieur ou égale aux dates de l'échéancier");
        $this->addMessage('impossible_date_retiraison', "La date limite doit être supérieur ou égale à la date de debut de retiraison");
        $this->addMessage('echeancier_date', "Vous devez saisir les dates de votre échéancier");
        $this->addMessage('impossible_campagne', "Un contrat pluriannuel doit s'appliquer sur au moins deux campagnes");
    }

    protected function doClean($values) {
    	$errorSchema = new sfValidatorErrorSchema($this);
    	$hasError = false;

    	if ($values['contrat_pluriannuel'] == 1 && isset($values['reference_contrat_pluriannuel']) && !$values['reference_contrat_pluriannuel']) {
    	    $errorSchema->addError(new sfValidatorError($this, 'required'), 'reference_contrat_pluriannuel');
    	    $hasError = true;
    	}

        if (isset($values['pluriannuel_campagne_debut']) && isset($values['pluriannuel_campagne_fin'])) {
            if ($values['pluriannuel_campagne_fin'] < 2) {
                $errorSchema->addError(new sfValidatorError($this, 'impossible_campagne'), 'pluriannuel_campagne_debut');
        	    $hasError = true;
            }
        }

    	if ($hasError) {
    		throw new sfValidatorErrorSchema($this, $errorSchema);
    	}
        return $values;
    }
}
