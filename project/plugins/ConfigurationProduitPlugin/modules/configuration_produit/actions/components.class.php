<?php
class configuration_produitComponents extends sfComponents 
{
    public function executeCatalogueProduit() 
    {
    	ConfigurationClient::getCurrent();
  		$this->produits = $this->configurationProduits->declaration->getProduits(false, true);
    }
    
	public function executePrestationsProduit() 
    {
    	$this->prestations = array();
    	foreach ($this->configurationProduits->getOrAdd('prestations') as $interpro => $produits) {
    		$key = str_replace('INTERPRO-', '', $interpro);
    		if (!isset($this->prestations[$key])) {
    			$this->prestations[$key] = array();
    		}
    		foreach ($produits as $produit) {
    			if (!in_array($produit->appellation, $this->prestations[$key])) {
    				$this->prestations[$key][] = $produit->appellation; 
    			}
    		}
    		
    	}
    }
}