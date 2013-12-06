<?php

class VracMarcheValidator extends sfValidatorBase {

    public function configure($options = array(), $messages = array()) {
        $this->addOption('determination_prix_field', 'determination_prix');
    }
    
	protected function getTypePrixNeedDetermination() {

      return array("objectif", "acompte");
    }
    
    protected function doClean($values) {
    	$errorSchema = new sfValidatorErrorSchema($this);
    	$hasError = false;
    	if (isset($values['type_prix']) && in_array($values['type_prix'], $this->getTypePrixNeedDetermination())) {
    		if (isset($values['determination_prix']) && !($values['determination_prix'])) {
    			$errorSchema->addError(new sfValidatorError($this, 'required'), 'determination_prix');
    			$hasError = true;
    		}
    	}
    	if (isset($values['volume_propose']) && $values['volume_propose'] <= 0) {
    			$errorSchema->addError(new sfValidatorError($this, 'required'), 'volume_propose');
    			$hasError = true;
    	}
    	if (isset($values['prix_unitaire']) && $values['prix_unitaire'] <= 0) {
    			$errorSchema->addError(new sfValidatorError($this, 'required'), 'prix_unitaire');
    			$hasError = true;
    	}
    	if ($hasError) {
    		throw new sfValidatorErrorSchema($this, $errorSchema);
    	}
        return $values;
    }

}
