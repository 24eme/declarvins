<?php

class VracLotValidator extends sfValidatorBase {

    public function configure($options = array(), $messages = array()) {
        $this->addMessage('impossible_millesime', "Le total des pourcentages millésime doit être égal à 100%");
    }
    
    protected function doClean($values) {	
    	$errorSchema = new sfValidatorErrorSchema($this);
    	$hasError = false;
    	$pourcentage = 0;
        if (is_array($values['millesimes']) && $values['assemblage']) {
    		foreach ($values['millesimes'] as $millesime) {
    			if ($millesime['pourcentage']) {
    				$pourcentage += $millesime['pourcentage'];
    			}
    		}
	        if ($pourcentage != 100) {
	        	//throw new sfValidatorErrorSchema($this, array(new sfValidatorError($this, 'impossible_millesime')));
    					$errorSchema->addError(new sfValidatorError($this, 'impossible_millesime'));
    					$hasError = true;
	        }
        }
        if (isset($values['presence_allergenes']) && $values['presence_allergenes']) {
        	if (isset($values['allergenes']) && !$values['allergenes']) {
        		//throw new sfValidatorErrorSchema($this, array('allergenes' => new sfValidatorError($this, 'required')));
    					$errorSchema->addError(new sfValidatorError($this, 'required'), 'allergenes');
    					$hasError = true;
        	}
        }
        if (isset($values['metayage']) && $values['metayage']) {
        	if (isset($values['bailleur']) && !$values['bailleur']) {
        		//throw new sfValidatorErrorSchema($this, array('bailleur' => new sfValidatorError($this, 'required')));
    					$errorSchema->addError(new sfValidatorError($this, 'required'), 'bailleur');
    					$hasError = true;
        	}
        }
    	if ($hasError) {
    		throw new sfValidatorErrorSchema($this, $errorSchema);
    	}
        return $values;
    }

}
