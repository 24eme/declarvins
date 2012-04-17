<?php
class ProduitLabelCollectionForm extends sfForm
{
  public function configure()
  {
    if (!$labels = $this->getOption('labels'))
      throw new InvalidArgumentException('You must provide a label node.');
    $hasItem = false;
    $key = 0;
    foreach ($labels as $key => $code_label) {
    	$this->embedForm ($key, new ProduitLabelForm(null, array('code_label' => $code_label)));
    	$hasItem = true;
    }
    if ($hasItem) {
    	$key++;
    }
    $this->embedForm ($key, new ProduitLabelForm());
  }
}