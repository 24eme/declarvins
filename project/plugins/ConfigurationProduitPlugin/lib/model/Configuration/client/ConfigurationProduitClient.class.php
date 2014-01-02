<?php

class ConfigurationProduitClient extends acCouchdbClient 
{
	const PREFIXE_ID = 'CONFIGURATION-PRODUITS';
	
	public static function getInstance() 
	{
	  	return acCouchdbManager::getClient("ConfigurationProduit");
	}
	
	public function buildId($interpro)
	{
		return self::PREFIXE_ID.'-'.$this->getIdentifiantInterpro($interpro);
	}
	
	public function getOrCreate($interpro)
	{
		$configurationProduits = $this->getByInterpro($interpro);
		if (!$configurationProduits) {
			$configurationProduits = new ConfigurationProduit();
	  		$configurationProduits->interpro = $interpro;
		}
		return $configurationProduits;
	}
	
	public function getByInterpro($interpro)
	{
		return $this->find($this->buildId($interpro));
	}
	
	private function getIdentifiantInterpro($interpro)
	{
		return str_replace('INTERPRO-', '', $interpro);
	}
}
