<?php

class VracTransactionValidator extends sfValidatorBase {

    public function configure($options = array(), $messages = array()) {
        $this->addMessage('impossible_volume', "La somme des volumes ne correspond pas au volume total proposé");
        $this->addMessage('impossible_date', "La date limite doit être supérieur ou égale aux dates de retiraison des cuves");
    }
    
    protected function doClean($values) {
    	$errorSchema = new sfValidatorErrorSchema($this);
    	$hasError = false;
    	$total = 0;
    	$hasDateLimiteRetiraison = (isset($values['date_limite_retiraison']) && $values['date_limite_retiraison']);
    	if ($hasDateLimiteRetiraison) {
    		$date_limite_retiraison = new DateTime($values['date_limite_retiraison']);
    	}
    	$isDateSup = false;
    	if (is_array($values['lots'])) {
	    	foreach ($values['lots'] as $lot) {
		        if (is_array($lot['cuves'])) {
		    		foreach ($lot['cuves'] as $cuve) {
		    			if ($cuve['volume']) {
		    				$total += $cuve['volume'];
		    			}
		    			if ($hasDateLimiteRetiraison && $cuve['date']) {
		    				$d = new DateTime($cuve['date']);
	    					if ($d->format('Ymd') > $date_limite_retiraison->format('Ymd')) {
	    						$isDateSup = true;
	    					}
		    			}
		    		}
		        }
	    	}
	        if ($isDateSup) {
	        	//throw new sfValidatorErrorSchema($this, array(new sfValidatorError($this, 'impossible_date')));
    					$errorSchema->addError(new sfValidatorError($this, 'impossible_date'));
    					$hasError = true;
	        }
	        if ($total != $values['volume_propose']) {
	        	//throw new sfValidatorErrorSchema($this, array(new sfValidatorError($this, 'impossible_volume')));
    					$errorSchema->addError(new sfValidatorError($this, 'impossible_volume'));
    					$hasError = true;
	        }
    	}
    	if ($hasError) {
    		throw new sfValidatorErrorSchema($this, $errorSchema);
    	}
        
        return $values;
    }

}
