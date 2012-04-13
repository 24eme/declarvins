<?php
class ProduitDepartementCollectionForm extends sfForm
{
  public function configure()
  {
    if (!$departements = $this->getOption('departements'))
      throw new InvalidArgumentException('You must provide a departement node.');
    for ($i=0; $i<=$this->getOption('nbDepartement', 1) ; $i++) {
    	$this->embedForm ($i, new ProduitDepartementForm());
    }
  }
}