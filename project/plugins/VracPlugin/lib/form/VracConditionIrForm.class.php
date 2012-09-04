<?php
class VracConditionIrForm extends VracConditionForm 
{
    public function configure() {
        parent::configure();

        unset($this['delai_paiement']);
        //unset($this['reference_contrat_pluriannuel']);
        unset($this['clause_reserve_retiraison']);
    }
}