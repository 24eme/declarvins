<?php
class ProduitDroitCollectionForm extends sfForm
{
  public function configure()
  {
    if (!$droits = $this->getOption('droits'))
      throw new InvalidArgumentException('You must provide a droit node.');
    $hasItem = false;
    $key = 0;
    foreach ($droits as $key => $droit) {
    	$this->embedForm ($key, new ProduitDroitForm(null, array('droit' => $droit)));
    	$hasItem = true;
    }
    if ($hasItem) {
    	$key++;
    }
    $this->embedForm ($key, new ProduitDroitForm());
  }
}