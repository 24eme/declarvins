<?php
class ConfigurationProduitDepartementCollectionForm extends sfForm
{
	public function configure()
  	{
    	$hasItem = false;
	    $key = 0;
	    $departements = $this->getOption('departements');
	    foreach ($departements as $departement) {
	    	$this->embedForm ($key, new ConfigurationProduitDepartementForm(null, array('departement' => $departement)));
	    	$hasItem = true;
	    	$key++;
	    }
	    if ($hasItem) {
	    	$key++;
	    }
	    $this->embedForm ($key, new ConfigurationProduitDepartementForm());
	    $nbDepartement = $this->getOption('nb');
	    if ($nbDepartement && $nbDepartement > $key) {
	    	$key++;
	    	for ($i = $key; $i <= $nbDepartement; $i++) {
	    		$this->embedForm ($key, new ConfigurationProduitDepartementForm());
	    		$key++;
	    	}
	    }
  	}
}