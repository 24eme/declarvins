<?php
class VracMarcheIrForm extends VracForm 
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
		$this->getWidget('type_transaction')->setOption('expanded', true);
		$this->getWidget('labels')->setOption('expanded', true);
		$this->getWidget('mentions')->setOption('expanded', true);
		$this->widgetSchema->setNameFormat('vrac_marche[%s]');
    }
}