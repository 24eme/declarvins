<?php
class ConfigurationProduitOrganismeCollectionForm extends sfForm
{
  public function configure()
  {
    $hasItem = false;
    $key = 0;
	$organismes = $this->getOption('organismes');
    foreach ($organismes as $organisme) {
    	$this->embedForm ($key, new ConfigurationProduitOrganismeForm(null, array('organisme' => $organisme)));
    	$hasItem = true;
    	$key++;
    }
    if ($hasItem) {
    	$key++;
    }
    $this->embedForm ($key, new ConfigurationProduitOrganismeForm());
  	$nbDroit = $this->getOption('nb');
    if ($nbDroit && $nbDroit > $key) {
    	$key++;
    	for ($i = $key; $i <= $nbDroit; $i++) {
    		$this->embedForm ($key, new ConfigurationProduitOrganismeForm());
    		$key++;
    	}
    } 
  }
}