<?php
class ConfigurationProduitDroitCollectionForm extends sfForm
{
  public function configure()
  {
    $hasItem = false;
    $key = 0;
	$droits = $this->getOption('droits');
	$type = $this->getOption('type');
    foreach ($droits as $droit) {
    	$this->embedForm ($key, new ConfigurationProduitDroitForm(null, array('type' => $type, 'droit' => $droit)));
    	$hasItem = true;
    	$key++;
    }
    if ($hasItem) {
    	$key++;
    }
    $this->embedForm ($key, new ConfigurationProduitDroitForm(null, array('type' => $type)));
  	$nbDroit = $this->getOption('nb');
    if ($nbDroit && $nbDroit > $key) {
    	$key++;
    	for ($i = $key; $i <= $nbDroit; $i++) {
    		$this->embedForm ($key, new ConfigurationProduitDroitForm(null, array('type' => $type)));
    		$key++;
    	}
    } 
  }
}