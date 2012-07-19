<?php
class VracTransactionCivpForm extends VracForm 
{
   	public function configure()
    {
		parent::configure();
		$this->useFields(array(
           'export',
           'commentaires',
           'lots'
		));
		$this->widgetSchema->setNameFormat('vrac_transaction[%s]');
    }
}