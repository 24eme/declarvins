<?php
class VracMarcheIvseForm extends VracForm 
{
   	public function configure()
    {
		parent::configure();
		$this->useFields(array(
           'type_transaction',
           'produit',
	       'labels',
           'mentions',
           'volume_propose',
	       'annexe'
		));
		$this->widgetSchema->setNameFormat('vrac_marche[%s]');
    }
}