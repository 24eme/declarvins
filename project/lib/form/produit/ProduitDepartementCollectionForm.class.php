<?php
class ProduitDepartementCollectionForm extends sfForm
{
  public function configure()
  {
    if (!$departements = $this->getOption('departements'))
      throw new InvalidArgumentException('You must provide a departement node.');
    $hasItem = false;
    $key = 0;
    foreach ($departements as $key => $departement) {
    	$this->embedForm ($key, new ProduitDepartementForm(null, array('departement' => $departement)));
    	$hasItem = true;
    }
    if ($hasItem) {
    	$key++;
    }
    $this->embedForm ($key, new ProduitDepartementForm());
  }
}