<?php

class VracProduitValidator extends sfValidatorBase {

    public function configure($options = array(), $messages = array()) {
        $this->addMessage('interpro', "Ce produit n'est pas disponible pour votre interpro");
    }
    
    protected function doClean($values) {
    	$interpros = InterproClient::getInstance()->getInterpros();
    	if (isset($values['produit']) && !empty($values['produit'])) {
    		$conf = ConfigurationClient::getCurrent();
    		$confProduit = $conf->get($values['produit']);
    		$interproProduit = $confProduit->getGerantInterpro();
    		if (!in_array($interproProduit->get('_id'), $interpros)) {
    			throw new sfValidatorErrorSchema($this, array($this->getOption('produit') => new sfValidatorError($this, 'interpro')));
    		}
    	}
        return $values;
    }

}
