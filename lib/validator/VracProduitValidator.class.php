<?php

class VracProduitValidator extends sfValidatorBase {

    public function configure($options = array(), $messages = array()) {
        $this->addMessage('format_millesime', "Le millésime doit être renseigné sur quatre chiffres");
        $this->addMessage('date_millesime', "Millésime invalide");
    }
    
    protected function doClean($values) {
    	$errorSchema = new sfValidatorErrorSchema($this);
    	$hasError = false;
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
