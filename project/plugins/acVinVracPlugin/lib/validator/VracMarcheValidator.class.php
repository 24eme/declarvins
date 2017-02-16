<?php

class VracMarcheValidator extends sfValidatorBase {

    public function configure($options = array(), $messages = array()) {
        $this->addOption('determination_prix_field', 'determination_prix');
        $this->addOption('determination_prix_date_field', 'determination_prix_date');
    }
    
	protected function getTypePrixNeedDetermination() {

      return array("objectif", "acompte");
    }
    
    protected function doClean($values) {
    	$errorSchema = new sfValidatorErrorSchema($this);
    	$hasError = false;
    	if (in_array('mercuriale_mois', array_keys($values)) && in_array('mercuriale_annee', array_keys($values))) {
    		if ($values['mercuriale_mois'] && !$values['mercuriale_annee']) {
    			$errorSchema->addError(new sfValidatorError($this, 'required'), 'mercuriale_annee');
    			$hasError = true;
    		}
    		if (!$values['mercuriale_mois'] && $values['mercuriale_annee']) {
    			$errorSchema->addError(new sfValidatorError($this, 'required'), 'mercuriale_mois');
    			$hasError = true;
    		}
    	}
    	if ($values['type_transaction'] == 'raisin' && !$values['poids']) {
    		$errorSchema->addError(new sfValidatorError($this, 'required'), 'poids');
    		$hasError = true;
    	}
    	if (isset($values['type_prix']) && in_array($values['type_prix'], $this->getTypePrixNeedDetermination())) {
    		if (isset($values['determination_prix']) && !($values['determination_prix'])) {
    			$errorSchema->addError(new sfValidatorError($this, 'required'), 'determination_prix');
    			$hasError = true;
    		}
    		if (!$values['determination_prix_date']) {
    			$errorSchema->addError(new sfValidatorError($this, 'required'), 'determination_prix_date');
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
