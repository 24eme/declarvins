<?php
class VracMarcheCivpForm extends VracMarcheForm 
{

    public function configure() {
        parent::configure();
		$this->widgetSchema->setLabel('type_transaction', 'Type de produit:');
    }
}