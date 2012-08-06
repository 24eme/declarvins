<?php
class VracMarcheForm extends VracForm 
{
   	public function configure()
    {
		parent::configure();
		$this->useFields(array(
           'type_transaction',
           'produit',
	       'labels',
           'mentions',
		   'prix_unitaire',
           'volume_propose',
		   'prix_total',
		   'has_transaction'
		));
		$this->widgetSchema->setNameFormat('vrac_marche[%s]');
    }
}