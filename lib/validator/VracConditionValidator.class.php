<?php

class VracConditionValidator extends sfValidatorBase {

    public function configure($options = array(), $messages = array()) {
        $this->addMessage('impossible_volume', "La somme des volumes ne correspond pas au volume total proposé");
        $this->addMessage('impossible_date', "La date limite doit être supérieur ou égale aux dates de l'échéancier");
        $this->addMessage('echeancier_date', "Vous devez saisir les dates de votre échéancier");
    }
    
    protected function doClean($values) {
        
    	if ($values['conditions_paiement'] == VracClient::ECHEANCIER_PAIEMENT) {
    		$total = 0;
    		if (is_array($values['paiements'])) {
		    	foreach ($values['paiements'] as $key => $paiement) {
		    		if ($paiement['volume']) {
		    			$total += $paiement['volume'];
		    		}
		    		if (!$paiement['date']) {
	        			throw new sfValidatorErrorSchema($this, array(new sfValidatorError($this, 'echeancier_date')));
		    		}
		    	}
    		}
	        if ($total != $values['volume_propose']) {
	        	throw new sfValidatorErrorSchema($this, array(new sfValidatorError($this, 'impossible_volume')));
	        }
    	}
    	$isDateSup = false;
    	if (isset($values['date_limite_retiraison']) && $values['date_limite_retiraison']) {
    		$date_limite_retiraison = new DateTime($this->getDateEn($values['date_limite_retiraison']));
    		if ($values['conditions_paiement'] == VracClient::ECHEANCIER_PAIEMENT) {
    			if (is_array($values['paiements'])) {
	    			foreach ($values['paiements'] as $paiement) {
	    				if ($date = $paiement['date']) {
	    					$d = new DateTime($this->getDateEn($date));
	    					if ($d->format('Ymd') > $date_limite_retiraison->format('Ymd')) {
	    						$isDateSup = true;
	    					}
	    				} 
	    			}
    			}
    		}
    	}
        if ($isDateSup) {
        	throw new sfValidatorErrorSchema($this, array('date_limite_retiraison' => new sfValidatorError($this, 'impossible_date')));
        }
        return $values;
    }
    
	protected function getDateEn($date) {
		$tabDate = explode('/', $date);
		return $tabDate[2].'-'.$tabDate[1].'-'.$tabDate[0];
	}
}
