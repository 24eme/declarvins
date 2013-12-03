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
        
    	if ($values['conditions_paiement'] == VracClient::ECHEANCIER_PAIEMENT) {
    		if (is_array($values['paiements'])) {
		    	foreach ($values['paiements'] as $key => $paiement) {
		    		if (!$paiement['date']) {
	        			//throw new sfValidatorErrorSchema($this, array(new sfValidatorError($this, 'echeancier_date')));
    					$errorSchema->addError(new sfValidatorError($this, 'echeancier_date'));
    					$hasError = true;
		    		}
		    	}
    		}
    	}
    	$isDateSup = false;
    	if (isset($values['date_limite_retiraison']) && $values['date_limite_retiraison']) {
    		$date_limite_retiraison = new DateTime($values['date_limite_retiraison']);
    		if (isset($values['date_debut_retiraison']) && $values['date_debut_retiraison']) {
    			$date_debut_retiraison = new DateTime($values['date_debut_retiraison']);
    			if ($date_debut_retiraison->format('Ymd') > $date_limite_retiraison->format('Ymd')) {
    				//throw new sfValidatorErrorSchema($this, array('date_limite_retiraison' => new sfValidatorError($this, 'impossible_date_retiraison')));
    					$errorSchema->addError(new sfValidatorError($this, 'impossible_date_retiraison'), 'date_limite_retiraison');
    					$hasError = true;
    			}
    		}
    		if ($values['conditions_paiement'] == VracClient::ECHEANCIER_PAIEMENT) {
    			if (is_array($values['paiements'])) {
	    			foreach ($values['paiements'] as $paiement) {
	    				if ($date = $paiement['date']) {
	    					$d = new DateTime($date);
	    					if ($d->format('Ymd') > $date_limite_retiraison->format('Ymd')) {
	    						$isDateSup = true;
	    					}
	    				} 
	    			}
    			}
    		}
    	}
        if ($isDateSup) {
        	//throw new sfValidatorErrorSchema($this, array('date_limite_retiraison' => new sfValidatorError($this, 'impossible_date')));
    					//$errorSchema->addError(new sfValidatorError($this, 'impossible_date'), 'date_limite_retiraison');
    					//$hasError = true;
        }
    	if ($hasError) {
    		throw new sfValidatorErrorSchema($this, $errorSchema);
    	}
        return $values;
    }
}
