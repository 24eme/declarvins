<?php
class VracMarcheForm extends VracForm 
{
   	public function configure()
    {
		parent::configure();
		$this->useFields(array(
           'type_transaction',
           'produit',
		   'millesime',
	       'labels',
           'mentions',
		   'prix_unitaire',
           'volume_propose',
		   'prix_total',
       'type_prix',
		   'has_transaction',
       'annexe'
		));
		$this->widgetSchema->setNameFormat('vrac_marche[%s]');

        if (count($this->getTypesTransaction()) < 2) {
            unset($this['type_transaction']);
        }
    }

    protected function doUpdateObject($values) {
        parent::doUpdateObject($values);

        $types_transaction = $this->getTypesTransaction();
        if (count($types_transaction) == 1) {
            foreach($types_transaction as $key => $value) {
                $this->getObject()->type_transaction = $key;
            }
        }
    }
}