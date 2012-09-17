<?php

class VracDateLimiteValidator extends sfValidatorBase {

    public function configure($options = array(), $messages = array()) {
        $this->addMessage('impossible', "La date limite doit être supérieur ou égale aux dates de l'échéancier");
        $this->addOption('date_limite_retiraison_field', 'date_limite_retiraison');
    }
    protected function getDateEn($dateFr) {
    	$date = explode('/', $dateFr);
    	return $date[2].'-'.$date[1].'-'.$date[0];
    }
    protected function doClean($values) {
    	$isDateSup = false;
    	if (isset($values['date_limite_retiraison']) && $values['date_limite_retiraison']) {
    		$date_limite_retiraison = new DateTime($this->getDateEn($values['date_limite_retiraison']));
    		if (isset($values['paiements'])) {
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

        if ($isDateSup) {
        	throw new sfValidatorErrorSchema($this, array($this->getOption('date_limite_retiraison_field') => new sfValidatorError($this, 'impossible')));
        }
        
        return $values;
    }

}
