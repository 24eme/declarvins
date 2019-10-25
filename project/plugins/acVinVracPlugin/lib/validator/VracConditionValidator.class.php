<?php

class VracConditionValidator extends sfValidatorBase {

    public function configure($options = array(), $messages = array()) {
        $this->addMessage('impossible_volume', "La somme des volumes ne correspond pas au volume total proposé");
        $this->addMessage('impossible_date', "La date limite doit être supérieur ou égale aux dates de l'échéancier");
        $this->addMessage('impossible_date_retiraison', "La date limite doit être supérieur ou égale à la date de debut de retiraison");
        $this->addMessage('echeancier_date', "Vous devez saisir les dates de votre échéancier");
    }
    
    protected function doClean($values) {
    	$errorSchema = new sfValidatorErrorSchema($this);
    	$hasError = false;
    	
    	if ($hasError) {
    		throw new sfValidatorErrorSchema($this, $errorSchema);
    	}
        return $values;
    }
}
