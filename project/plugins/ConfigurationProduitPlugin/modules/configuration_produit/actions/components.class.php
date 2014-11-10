<?php
class configuration_produitComponents extends sfComponents 
{
    public function executeCatalogueProduit() 
    {
    	ConfigurationClient::getCurrent();
  		$this->produits = $this->configurationProduits->declaration->getProduits(false, true);
    }
}