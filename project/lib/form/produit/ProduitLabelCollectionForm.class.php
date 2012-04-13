<?php
class ProduitLabelCollectionForm extends sfForm
{
  public function configure()
  {
    if (!$labels = $this->getOption('labels'))
      throw new InvalidArgumentException('You must provide a label node.');
    for ($i=0; $i<$this->getOption('nbLabel', 1); $i++) {
    	$this->embedForm ($i, new ProduitLabelForm());
    }
  }
}