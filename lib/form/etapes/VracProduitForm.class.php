<?php
class VracProduitForm extends VracForm 
{
   	public function configure()
    {
    		parent::configure();
    		$this->useFields(array(
               'produit',
    		   'millesime'
    		));
  		    $this->validatorSchema->setPostValidator(new VracProduitValidator());
    		$this->widgetSchema->setNameFormat('vrac_produit[%s]');
    }
    protected function doUpdateObject($values) {
        parent::doUpdateObject($values);
        $this->getObject()->update();
    }
}