<?php
class VracTransactionForm extends VracForm 
{
   	public function configure()
    {
		parent::configure();
		$this->useFields(array(
           'commentaires',
           'lots'
		));
		$this->widgetSchema->setNameFormat('vrac_transaction[%s]');
    }
}