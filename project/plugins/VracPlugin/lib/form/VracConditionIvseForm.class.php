<?php
class VracConditionIvseForm extends VracConditionForm 
{
    public function configure() {
        parent::configure();
		$this->getWidget('conditions_paiement')->setLabel('Conditions de vente');
        unset($this['clause_reserve_retiraison']);
        unset($this['date_debut_retiraison']);
    }
}