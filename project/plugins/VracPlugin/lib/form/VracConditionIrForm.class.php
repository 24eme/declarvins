<?php
class VracConditionIrForm extends VracConditionForm 
{
    public function configure() {
        parent::configure();
        unset($this['has_transaction']);
        unset($this['delai_paiement']);
        unset($this['contrat_pluriannuel']);
        unset($this['clause_reserve_retiraison']);
        unset($this['date_debut_retiraison']);
    }


    public function conditionneDelaiContrat() {
      return false;
    }
}