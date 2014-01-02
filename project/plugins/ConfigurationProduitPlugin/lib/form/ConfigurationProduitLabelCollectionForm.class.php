<?php
class ConfigurationProduitLabelCollectionForm extends sfForm
{
  public function configure()
  {
    $hasItem = false;
    $key = 0;
	$labels = $this->getOption('labels');
    foreach ($labels as $code_label => $libelle_label) {
    	$this->embedForm ($key, new ConfigurationProduitLabelForm(null, array('code_label' => $code_label, 'libelle_label' => $libelle_label)));
    	$hasItem = true;
    	$key++;
    }
    if ($hasItem) {
    	$key++;
    }
    $this->embedForm ($key, new ConfigurationProduitLabelForm());
    $nbLabel = $this->getOption('nb');
    if ($nbLabel && $nbLabel > $key) {
    	$key++;
    	for ($i = $key; $i <= $nbLabel; $i++) {
    		$this->embedForm ($key, new ConfigurationProduitLabelForm());
    		$key++;
    	}
    }
  }
}