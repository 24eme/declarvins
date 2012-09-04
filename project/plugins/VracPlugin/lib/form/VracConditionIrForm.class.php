<?php
class VracConditionIrForm extends VracConditionForm 
{
    public function configure() {
        parent::configure();
		$this->getWidget('conditions_paiement')->setOption('multiple', false);
        unset($this['delai_paiement']);
        unset($this['contrat_pluriannuel']);
        unset($this['reference_contrat_pluriannuel']);
        unset($this['clause_reserve_retiraison']);
    }
}