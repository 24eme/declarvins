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
    		   'export',
    		   'prix_unitaire',
               'volume_propose',
    		   'prix_total',
    		   'part_cvo',
    		   'repartition_cvo_acheteur',
               'type_prix',
               'determination_prix',
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

        if (!in_array($this->getObject()->type_prix, $this->getTypePrixNeedDetermination())) {
          $this->getObject()->determination_prix = null;
        }

        $this->getObject()->update();
    }

    public function getTypePrixNeedDetermination() {

      return array("objectif", "acompte");
    }
}