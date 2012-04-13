<?php
class ProduitDroitCollectionForm extends sfForm
{
  public function configure()
  {
    if (!$droits = $this->getOption('droits'))
      throw new InvalidArgumentException('You must provide a droit node.');
      
    for ($i=0; $i<$this->getOption('nbDroits', 1); $i++) {
    	$this->embedForm ($i, new ProduitDroitForm($droits[$i]));
    }
    $this->embedForm ($i, new ProduitDroitForm($droits->add()));
  }
}