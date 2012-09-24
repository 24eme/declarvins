<?php

class VracEcheancierVolumesValidator extends sfValidatorBase {

    public function configure($options = array(), $messages = array()) {
        $this->addMessage('impossible', "La somme des volumes ne correspond pas au volume total proposé");
    }
    
    protected function doClean($values) {
    	$total = 0;
    	if ($values['conditions_paiement'] == VracClient::ECHEANCIER_PAIEMENT) {
	    	foreach ($values['paiements'] as $paiement) {
	    			if ($paiement['volume']) {
	    				$total += $paiement['volume'];
	    			}
	    	}
	
	        if ($total != $values['volume_propose']) {
	        	throw new sfValidatorErrorSchema($this, array($this->getOption('paiements') => new sfValidatorError($this, 'impossible')));
	        }
    	}
        return $values;
    }

}
