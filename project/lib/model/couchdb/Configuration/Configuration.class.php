<?php
/**
 * Model for Configuration
 *
 */

class Configuration extends BaseConfiguration {

	const DEFAULT_KEY = 'DEFAUT';

    public function constructId() {
        $this->set('_id', "CONFIGURATION");
    }

    public function getProduitLibelles($hash) {
    	$libelles = $this->store('produits_libelles', array($this, 'getProduitsLibelleAbstract'));
    	if(array_key_exists($hash, $libelles)) {

    		return $libelles[$hash];
    	} else {
    		
    		return null;
    	}
    }

    protected function getProduitsLibelleAbstract() {
    	$results = ConfigurationClient::getInstance()->findProduits();
    	$libelles = array();

    	foreach($results->rows as $item) {
    		$libelles['/'.$item->key[5]] = $item->value;
    	}

    	return $libelles;
    }
}