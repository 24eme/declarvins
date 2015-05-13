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
    if (!isset($values['millesime']) || empty($values['millesime'])) {
                if (isset($values['non_millesime']) && is_null($values['non_millesime'])) {
                        $errorSchema->addError(new sfValidatorError($this, 'millesime_inexistant'), 'millesime');
                        $hasError = true;
                }
        }

        if (isset($values['millesime']) && !empty($values['millesime'])) {
                if (strlen($values['millesime']) != 4) {
                        //throw new sfValidatorErrorSchema($this, array($this->getOption('millesime') => new sfValidatorError($this, 'format_millesime')));
                                        $errorSchema->addError(new sfValidatorError($this, 'format_millesime'), 'millesime');
                                        $hasError = true;
                }
                if ($values['millesime'] > (date('Y')+1)) {
                        //throw new sfValidatorErrorSchema($this, array($this->getOption('millesime') => new sfValidatorError($this, 'date_millesime')));
                                        $errorSchema->addError(new sfValidatorError($this, 'date_millesime'), 'millesime');
                                        $hasError = true;
                }
        }
    	if ($hasError) {
    		throw new sfValidatorErrorSchema($this, $errorSchema);
    	}
        return $values;
    }

}
