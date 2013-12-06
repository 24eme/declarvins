<?php

class VracProduitValidator extends sfValidatorBase {

    public function configure($options = array(), $messages = array()) {
        $this->addMessage('interpro', "Ce produit n'est pas disponible pour votre interpro");
        $this->addMessage('format_millesime', "Le millésime doit être renseigné sur quatre chiffres");
        $this->addMessage('date_millesime', "Millésime invalide");
    }
    
    protected function doClean($values) {
    	$errorSchema = new sfValidatorErrorSchema($this);
    	$hasError = false;
    	$interpros = InterproClient::getInstance()->getInterpros();
    	if (isset($values['produit']) && !empty($values['produit'])) {
    		$conf = ConfigurationClient::getCurrent();
    		$confProduit = $conf->get($values['produit']);
    		$interproProduit = $confProduit->getGerantInterpro();
    		if (!in_array($interproProduit->get('_id'), $interpros)) {
    			//throw new sfValidatorErrorSchema($this, array($this->getOption('produit') => new sfValidatorError($this, 'interpro')));
    					$errorSchema->addError(new sfValidatorError($this, 'interpro'), 'produit');
    					$hasError = true;
    		}
    	}
    	if (isset($values['millesime']) && !empty($values['millesime'])) {
    		if (strlen($values['millesime']) != 4) {
    			//throw new sfValidatorErrorSchema($this, array($this->getOption('millesime') => new sfValidatorError($this, 'format_millesime')));
    					$errorSchema->addError(new sfValidatorError($this, 'format_millesime'), 'millesime');
    					$hasError = true;
    		}
    		if ($values['millesime'] > (date('Y'))) {
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
