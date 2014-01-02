<?php
class configuration_produitComponents extends sfComponents 
{
    public function executeCatalogueProduit() 
    {
  		$this->produits = $this->configurationProduits->declaration->getProduits();
    }
}