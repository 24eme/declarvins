<?php
class VracMarcheForm extends VracForm 
{
   	public function configure()
    {
    		parent::configure();
    		$this->useFields(array(
               'type_transaction',
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
    		   'has_cotisation_cvo',
               'annexe'
    		));
		    $this->getObject()->has_cotisation_cvo = 1;
  		    $this->validatorSchema->setPostValidator(new VracMarcheValidator());
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
        if (!$this->getObject()->annexe) {
        	$this->getObject()->annexe = 0;
        }
        $this->getObject()->labels_libelle = $this->getConfiguration()->formatLabelsLibelle(array($this->getObject()->labels));
        $this->getObject()->mentions_libelle = $this->getConfiguration()->formatMentionsLibelle($this->getObject()->mentions);
        $this->getObject()->type_transaction_libelle = $this->getConfiguration()->formatTypesTransactionLibelle(array($this->getObject()->type_transaction));
        $this->getObject()->update();
    }
    protected function updateDefaultsFromObject() {
      parent::updateDefaultsFromObject();    
      if (is_null($this->getObject()->type_transaction)) {
        $this->setDefault('type_transaction', VracClient::TRANSACTION_DEFAUT);
      }      
      if (is_null($this->getObject()->type_prix)) {
        $this->setDefault('type_prix', VracClient::PRIX_DEFAUT);
      }  
      if (is_null($this->getObject()->export)) {
        $this->setDefault('export', 0);
      }   
      if (is_null($this->getObject()->annexe)) {
        $this->setDefault('annexe', 0);
      }   
      if (is_null($this->getObject()->labels)) {
        $this->setDefault('labels', VracClient::LABEL_DEFAUT);
      }   
    }

    public function getTypePrixNeedDetermination() {

      return array("objectif", "acompte");
    }
}