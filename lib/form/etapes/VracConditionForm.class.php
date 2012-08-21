<?php
class VracConditionForm extends VracForm 
{
   	public function configure()
    {
  		parent::configure();
  		$this->useFields(array(
  	       'date_limite_retiraison',
             'commentaires_conditions',
             'conditions_paiement',
  	       'vin_livre',
             'contrat_pluriannuel',
             'reference_contrat_pluriannuel',
  	       'delai_paiement',
             'echeancier_paiement',
             'clause_reserve_retiraison',
  		   'paiements'
  		));
  		$this->widgetSchema->setNameFormat('vrac_condition[%s]');
    }

    protected function doUpdateObject($values) {
      if (!$values['echeancier_paiement']) {
        $values['paiements'] = array();
        $this->getObject()->remove('paiements');
        $this->getObject()->add('paiements');
      }

      parent::doUpdateObject($values); 

      if (!$this->isEcheanchierPaiementOptionnel()) {
        $this->getObject()->echeancier_paiement = 1;
      }
    }

    protected function updateDefaultsFromObject() {
      parent::updateDefaultsFromObject();    
      if (is_null($this->getObject()->echeancier_paiement)) {
        $this->setDefault('echeancier_paiement', 1);
      }  
    }

    public function isEcheanchierPaiementOptionnel() {

        return true;
    }
}