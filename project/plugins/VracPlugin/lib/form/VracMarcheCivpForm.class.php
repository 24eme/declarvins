<?php
class VracMarcheCivpForm extends VracMarcheForm 
{

    public function configure() {
        parent::configure();
		$this->getWidget('has_cotisation_cvo')->setDefault(0);
		$this->widgetSchema->setLabel('type_transaction', 'Type de produit:');
        unset($this['has_transaction']);
    }

    protected function doUpdateObject($values) {
        parent::doUpdateObject($values);

        $this->getObject()->has_transaction = 1;
    }
}